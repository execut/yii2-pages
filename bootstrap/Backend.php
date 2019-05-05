<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\crud\Bootstrap;
use execut\navigation\Component;
use execut\crud\navigation\Configurator;
use execut\pages\models\Page;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\i18n\PhpMessageSource;

class Backend extends Common
{
    public function getDefaultDepends()
    {
        return ArrayHelper::merge(parent::getDefaultDepends(), [
            'bootstrap' => [
                'crud' => [
                    'class' => Bootstrap::class,
                ],
            ],
        ]); // TODO: Change the autogenerated stub
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->bootstrapNavigation($app);
    }

    /**
     * @param $app
     */
    protected function bootstrapNavigation($app)
    {
        $module = $app->getModule('pages');
        if (!(!$app->user->isGuest && $module->adminRole === '@') && !$app->user->can($module->adminRole)) {
            return;
        }

        /**
         * @var Component $navigation
         */
        $navigation = $app->navigation;
        $navigation->addConfigurator([
            'class' => Configurator::class,
            'module' => 'pages',
            'moduleName' => 'Pages',
            'modelName' => Page::MODEL_NAME,
            'controller' => 'backend',
        ]);
    }
}