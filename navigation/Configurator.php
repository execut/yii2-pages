<?php
/**
 */

namespace execut\pages\navigation;


use execut\navigation\Component;
use execut\navigation\page\Home;

use execut\navigation\page\NotFound;
use execut\pages\models\Page;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Configurator implements \execut\navigation\Configurator
{
    public $module = 'pages';
    public $controller = 'frontend';
    public function configure(Component $navigation)
    {
        $currentModule = $this->getCurrentModule();
        if ($currentModule !== $this->module) {
            return;
        }

        $controller = \yii::$app->controller;
        if ($controller->id !== $this->controller) {
            return;
        }
        $pages = [
//            [
//                'class' => Home::class
//            ],
        ];
        $action = $controller->action;
        if ($action->id === 'error') {
            $pages[] = ArrayHelper::merge([
                'class' => NotFound::class,
            ], \yii::$app->getModule('pages')->notFoundPage);
        } else {
            $pageId = \yii::$app->request->getQueryParam('id');
            if ($pageId) {
                $pages = Page::getNavigationPages($pageId);
            }
        }

        foreach ($pages as $page) {
            $navigation->addPage($page);
        }

        $navigation->initMetatags();
    }

    /**
     * @return string
     */
    protected function getCurrentModule()
    {
        $currentModule = \Yii::$app->controller->module->id;
        return $currentModule;
    }
}