<?php
/**
 */

namespace execut\pages\controllers;
use execut\crud\params\Crud;
use execut\pages\models\Page;
use yii\web\Controller;

class BackendController extends Controller
{

    public function actions()
    {
        $crud = new Crud([
            'modelClass' => Page::class,
            'title' => 'Pages',
        ]);
        return $crud->actions();
    }
}