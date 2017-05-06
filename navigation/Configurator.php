<?php
/**
 */

namespace execut\pages\navigation;


use execut\navigation\Component;
use execut\navigation\page\Home;

use execut\navigation\page\NotFound;
use execut\pages\models\Page;
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
            [
                'class' => Home::class
            ],
        ];
        $action = $controller->action;
        if ($action->id === 'error') {
            $pages[] = [
                'class' => NotFound::class,
            ];
        } else {
            $pageId = \yii::$app->request->getQueryParam('id');
            if ($pageId) {
                $pages = Page::getNavigationPages($pageId);
            }
        }

        foreach ($pages as $page) {
            $navigation->addPage($page);
        }
    }

    /**
     * @return string
     */
    protected function getCurrentModule(): string
    {
        $currentModule = \Yii::$app->controller->module->id;
        return $currentModule;
    }
}