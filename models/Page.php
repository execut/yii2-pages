<?php

namespace execut\pages\models;

use execut\crudFields\Behavior;
use execut\crudFields\BehaviorStub;
use execut\crudFields\fields\Action;
use execut\crudFields\fields\Boolean;
use execut\crudFields\fields\Date;
use execut\crudFields\fields\DropDown;
use execut\crudFields\fields\Editor;
use execut\crudFields\fields\Field;
use execut\crudFields\fields\HasOneRelation;
use execut\crudFields\fields\HasOneSelect2;
use execut\crudFields\fields\Id;
use execut\crudFields\fields\reloader\Reloader;
use execut\crudFields\fields\reloader\Target;
use execut\crudFields\fields\reloader\type\Dependent;
use execut\crudFields\ModelsHelperTrait;
use execut\pages\crudFields\PageAddress;
use execut\pages\crudFields\pageAddress\Adapter;
use execut\pages\crudFields\pageAddress\adapter\Alias;
use execut\pages\crudFields\pageAddress\adapter\Simple;
use \execut\pages\models\base\Page as BasePage;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pages_pages".
 */
class Page extends ActiveRecord
{
    const CACHE_TAG = 'pages_pages';
    const MODEL_NAME = '{n,plural,=0{Pages} =1{Page} other{Pages}}';
    const TYPE_ALIAS = 2;
    const TYPE_SIMPLE = 1;
    use BehaviorStub, ModelsHelperTrait;
    public $address = null;
    public function behaviors()
    {
        $module = \yii::$app->getModule('pages');
        if ($module) {
            $pageFieldsPlugins = $module->getPageFieldsPlugins();
        } else {
            $pageFieldsPlugins = [];
        }

        $typeField = new DropDown([
            'attribute' => 'type',
            'data' => $this->getTypesList(),
            'required' => true,
        ]);

        $target = new Target($typeField);

        $reloader = new Reloader(new Dependent(), [$target]);

        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fields' => [
                    'class' => Behavior::class,
                    'plugins' => $pageFieldsPlugins,
                    'fields' => $this->getStandardFields(null, [
                        'address' => [
                            'class' => PageAddress::class,
                            'attribute' => 'address',
                            'reloaders' => [$reloader],
                        ],
                        'type' => $typeField,
                        'pagesPage' => [
                            'class' => HasOneSelect2::class,
                            'attribute' => 'pages_page_id',
                            'relation' => 'pagesPage',
                            'url' => [
                                '/pages/backend'
                            ],
                        ],
//                        'is_denied_for_indexation' => [
//                            'class' => Boolean::class,
//                            'attribute' => 'is_denied_for_indexation',
//                        ],
                    ])
                ],
                [
                    'class' => TimestampBehavior::class,
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'updated',
                    'value' => new Expression('NOW()'),
                ],
                # custom behaviors
            ]
        );
    }

    public static function filtrateAttribute($attribute, $value) {
        if ($attribute === 'type') {
            return (int) $value;
        }

        if ($attribute === 'no_index') {
            return (bool) $value;
        }

        return $value;
    }

    public function load($data, $formName = null)
    {
        foreach ($this->getAdapters() as $adapter) {
            $adapter->initPageFields($this);
        }

        $result = parent::load($data, $formName);

        if ($this->scenario === Field::SCENARIO_FORM) {
            $this->initParamsFromAddress();
        }

        $typeTarget = new Target($this->getField('type'));
        $addressField = $this->getField('address');

        if (empty($this->type)) {
            $addressField->getDetailViewField()->hide();
        }

        $addressTarget = new Target($addressField);
        $addressTarget->setWhenIsEmpty(true);
        $reloader = new Reloader(new Dependent(), [$typeTarget, $addressTarget]);
        foreach ($this->getAdapters() as $adapter) {
            $adapter->initPageFields($this);
            $type = $adapter->getKey();
            $fields = $adapter->getFields();
            foreach ($fields as $fieldName) {
                $field = $this->getField($fieldName);
                $field->addReloader($reloader);
                $detailViewField = $field->getDetailViewField();
                if ($type == $this->type) {
                    $detailViewField->show();
                } else {
                    $detailViewField->hide();
                }
            }
        }

        $this->trigger(Behavior::EVENT_AFTER_LOAD);

        return $result; // TODO: Change the autogenerated stub
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors); // TODO: Change the autogenerated stub
    }

    protected function isValidAddress() {
        return true;
    }

    protected function getTypesList() {
        $result = [];
        foreach ($this->getAdapters() as $adapter) {
            $result[$adapter->getKey()] = $adapter->getLabel();
        }

        return $result;
    }

    public function getAdapters() {

        $module = \yii::$app->getModule('pages');
        if ($module) {
            $result = $module->getPageAddressAdapters();
        } else {
            $result = [];
        }
        return ArrayHelper::merge([
            new Simple(),
        ], $result);
    }

    protected static $pagesCache = [];
    public static function getCache($id) {
        if (isset(self::$pagesCache[$id])) {
            return self::$pagesCache[$id];
        }
    }

    public static function setCache($model) {
        return self::$pagesCache[$model->id] = $model;
    }

    public function isHome() {
        return empty($this->getUrl());
    }

    public static function getNavigationPages($activePageId) {
        $result = [];
        if (!($page = self::getCache($activePageId))) {
            $query = self::find()->andWhere(['id' => $activePageId])->withParents()->isVisible();
            \yii::$app->getModule('pages')->applyCurrentPageScopes($query);
            $page = $query->one();
            self::setCache($page);
        }

        if (!$page) {
            return [];
        }

        do {
            $currentPage = $page->getNavigationPage();
            $result[] = $currentPage;
            if (count($result) == 1) {
                self::initCurrentNavigationPage($currentPage, $page);
            }
        } while ($page = $page->pagesPage);

        return array_reverse($result);
    }

    /**
     * @TODO Very bad
     *
     * @param $navigationPage
     * @param $pageModel
     */
    public static function initCurrentNavigationPage($navigationPage, $pageModel) {
        \yii::$app->getModule('pages')->initCurrentNavigationPage($navigationPage, $pageModel);
    }

    public function getNavigationPage() {
        $page = new \execut\pages\navigation\Page();
        $checkedAttributes = [
            'name',
            'keywords',
            'title',
            'description',
            'header',
            'text',
        ];

        $page->setUrl($this->getUrl());
        foreach ($checkedAttributes as $attribute) {
            if (!empty($this->$attribute)) {
                $setter = 'set' . ucfirst($attribute);
                $page->$setter($this->$attribute);
            }
        }

        $page->setTime(strtotime($this->getLastTime()));
        $page->model = $this;

        return $page;
    }

    public function getUrl() {
        return $this->getAdapter()->toString($this->attributes);
    }

    public function getLastTime() {
        if ($this->updated) {
            return $this->updated;
        }

        return $this->created;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages_pages';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagesPage()
    {
        return $this->hasOne(\execut\pages\models\Page::class, ['id' => 'pages_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(\execut\pages\models\Page::class, ['pages_page_id' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \execut\pages\models\queries\PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new \execut\pages\models\queries\PageQuery(get_called_class()))->cache(0, new TagDependency(['tags' => self::CACHE_TAG]));
    }

    public function beforeValidate()
    {
        if ($this->scenario === Field::SCENARIO_FORM) {
            $this->initParamsFromAddress();
        }

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function beforeSave($insert)
    {
        TagDependency::invalidate(\yii::$app->cache, [self::CACHE_TAG]);


        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @return Alias
     */
    public function getAdapter()
    {
        if (!empty($this->type)) {
            $adapters = $this->getAdapters();
            foreach ($adapters as $adapter) {
                if ($adapter->getKey() == $this->type) {
                    return $adapter;
                }
            }
        }
    }

    /**
     * @return Alias
     */
    protected function initParamsFromAddress()
    {
        if (!empty($this->address) && $this->isValidAddress()) {
            $adapter = $this->getAdapter();
            if ($adapter) {
                $params = $adapter->toArray($this->address);
                if ($params) {
                    foreach ($params as $attribute => $value) {
                        $this->$attribute = $value;
                    }
                }

                $this->address = null;
            }
        }
    }
}
