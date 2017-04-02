<?php
/**
 */

namespace execut\pages\controllers;


use execut\actions\Action;
use execut\actions\action\adapter\Delete;
use execut\actions\action\adapter\Edit;
use execut\actions\action\adapter\GridView;
use execut\crud\fields\Field;
use execut\pages\models\Page;
use execut\navigation\Behavior;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class BackendController extends Controller
{
    public function behaviors()
    {
        $mainPage = [
            'name' => 'Pages',
            'url' => [
                '/' . $this->uniqueId,
            ],
        ];
        $pages = [
            $mainPage
        ];
        return ArrayHelper::merge(parent::behaviors(), [
            'navigation' => [
                'class' => Behavior::class,
                'pages' => $pages,
            ],
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => Action::class,
                'adapter' => [
                    'class' => GridView::class,
                    'model' => [
                        'class' => Page::class,
                        'scenario' => Field::SCENARIO_GRID,
                    ],
                    'view' => [
                        'title' => 'Pages',
                    ],
                ],
            ],
            'update' => [
                'class' => Action::class,
                'on beforeRender' => function ($e) {
                    $model = $e->sender->adapter->model;
                    if ($model->isNewRecord) {
                        $name = 'Create page';
                    } else {
                        $name = 'Update ' . $model->name;
                    }

                    $this->addPage([
                        'name' => $name,
                    ]);
                },
                'adapter' => [
                    'class' => Edit::class,
                    'modelClass' => Page::class,
                    'scenario' => Field::SCENARIO_FORM,
                ],
            ],
            'delete' => [
                'class' => Action::class,
                'adapter' => [
                    'class' => Delete::class,
                    'modelClass' => Page::class,
                ],
            ],
        ]);
    }
}