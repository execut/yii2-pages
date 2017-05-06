<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\navigation\Component;
use execut\crud\navigation\Configurator;
use execut\yii\Bootstrap;
use yii\helpers\ArrayHelper;

class Backend extends Frontend
{
    public function getDefaultDepends()
    {
        return ArrayHelper::merge(parent::getDefaultDepends(), [
            'bootstrap' => [
                [
                    'class' => Bootstrap::class,
                ]
            ],
        ]);
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        /**
         * @var Component $navigation
         */
        $navigation = $app->navigation;
        $navigation->addConfigurator([
            'class' => Configurator::class,
            'module' => 'pages',
            'moduleName' => 'Pages',
            'modelName' => 'Page',
            'controller' => 'backend',
        ]);
    }
}