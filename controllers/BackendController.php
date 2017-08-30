<?php
/**
 */

namespace execut\pages\controllers;
use execut\crud\params\Crud;
use execut\pages\models\Page;
use yii\filters\AccessControl;
use yii\web\Controller;

class BackendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        $crud = new Crud([
            'modelClass' => Page::class,
            'module' => 'pages',
            'moduleName' => 'Pages',
            'modelName' => Page::MODEL_NAME,
        ]);
        return $crud->actions();
    }
}