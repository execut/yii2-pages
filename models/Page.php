<?php

namespace execut\pages\models;

use execut\crudFields\Behavior;
use execut\crudFields\BehaviorStub;
use execut\crudFields\fields\Action;
use execut\crudFields\fields\Boolean;
use execut\crudFields\fields\Date;
use execut\crudFields\fields\Editor;
use execut\crudFields\fields\HasOneRelation;
use execut\crudFields\fields\Id;
use \execut\pages\models\base\Page as BasePage;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pages_pages".
 */
class Page extends BasePage
{
    use BehaviorStub;
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'navigation' => [
                    'class' => \execut\navigation\Behavior::class,
                ],
                'fields' => [
                    'class' => Behavior::class,
                    'fields' => [
                        [
                            'class' => Id::class,
                            'attribute' => 'id',
                        ],
                        [
                            'required' => true,
                            'attribute' => 'name',
                        ],
                        [
                            'class' => HasOneRelation::class,
                            'attribute' => 'pages_page_id',
                            'url' => [
                                '/pages/backend'
                            ],
                        ],
                        [
                            'class' => Boolean::class,
                            'attribute' => 'visible',
                        ],
                        [
                            'attribute' => 'header',
                        ],
                        [
                            'attribute' => 'title',
                        ],
                        [
                            'attribute' => 'description',
                        ],
                        [
                            'attribute' => 'keywords',
                        ],
                        [
                            'class' => Editor::class,
                            'attribute' => 'text',
                        ],
                        [
                            'class' => Date::class,
                            'attribute' => 'created',
                        ],
                        [
                            'class' => Date::class,
                            'attribute' => 'updated',
                        ],
                        [
                            'class' => Action::class,
                        ],
                    ],
                ],
                # custom behaviors
            ]
        );
    }

    public static function findById($id) {
        $page = $currentPage = self::find()->andWhere(['id' => $id])->one();
        $navigationPages = [];
        do {
            $navigationPage = $page->getNavigationPage();
            $navigationPages[] = $navigationPage;
        } while ($page = $page->pagesPage);
        $navigationPages = array_reverse($navigationPages);
        $currentPage->getBehavior('navigation')->setPages($navigationPages);

        return $currentPage;
    }

    public function getNavigationPage() {
        $page = new \execut\navigation\Page([
            'name' => $this->name,
            'url' => [
                '/pages/frontend',
                'id' => $this->id,
            ],
        ]);
        $checkedAttributes = [
            'keywords',
            'title',
            'description',
            'header',
            'text',
        ];
        foreach ($checkedAttributes as $attribute) {
            if (!empty($this->$attribute)) {
                $page->$attribute = $this->$attribute;
            }
        }

        return $page;
    }
}
