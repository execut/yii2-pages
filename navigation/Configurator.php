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
use yii\web\NotFoundHttpException;

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
            $e = $this->findException();
            $pagesModule = \yii::$app->getModule('pages');
            $errorPage = \Yii::createObject(ArrayHelper::merge([
                'class' => NotFound::class,
                'text' => $e->getMessage(),
                'code' => $e->getCode(),
            ], $pagesModule->notFoundPage));

            $pages[] = $pagesModule->configureErrorPage($errorPage, $e);
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
     * Gets exception from the [[yii\web\ErrorHandler|ErrorHandler]] component.
     * In case there is no exception in the component, treat as the action has been invoked
     * not from error handler, but by direct route, so '404 Not Found' error will be displayed.
     * @return \Exception
     * @since 2.0.11
     */
    protected function findException()
    {
        if (($exception = \Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
        }

        return $exception;
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