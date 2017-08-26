<?php

namespace execut\pages\models;

use execut\crudFields\Behavior;
use execut\crudFields\BehaviorStub;
use execut\crudFields\fields\Action;
use execut\crudFields\fields\Boolean;
use execut\crudFields\fields\Date;
use execut\crudFields\fields\Editor;
use execut\crudFields\fields\HasOneRelation;
use execut\crudFields\fields\HasOneSelect2;
use execut\crudFields\fields\Id;
use execut\crudFields\ModelsHelperTrait;
use \execut\pages\models\base\Page as BasePage;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pages_pages".
 */
class Page extends BasePage
{
    use BehaviorStub, ModelsHelperTrait;
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fields' => [
                    'class' => Behavior::class,
                    'plugins' => \yii::$app->getModule('pages')->getPageFieldsPlugins(),
                    'fields' => $this->getStandardFields(null, [
                        [
                            'class' => HasOneSelect2::class,
                            'attribute' => 'pages_page_id',
                            'url' => [
                                '/pages/backend'
                            ],
                        ],
                        [
                            'class' => Action::class,
                        ],
                    ])
                ],
                # custom behaviors
            ]
        );
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

    public static function getNavigationPages($id) {
        $result = [];
        if (!($page = self::getCache($id))) {
            $page = self::find()->andWhere(['id' => $id])->withParents()->isVisible()->one();
            self::setCache($page);
        }

        if (!$page) {
            return [];
        }

        do {
            $currentPage = $page->getNavigationPage();
            $result[] = $currentPage;
        } while ($page = $page->pagesPage);

        return array_reverse($result);
    }

    public function getNavigationPage() {
        $page = new \execut\navigation\Page();
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

        return $page;
    }

    public function getUrl() {
        return [
            '/pages/frontend',
            'id' => $this->id,
        ];
    }

    public function getLastTime() {
        if ($this->updated) {
            return $this->updated;
        }

        return $this->created;
    }
}
