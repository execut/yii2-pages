<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\navigation\Component;
use execut\crud\navigation\Configurator;
use yii\filters\AccessControl;

class Backend extends Frontend
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $this->bootstrapNavigation($app);
    }

    /**
     * @param $app
     */
    protected function bootstrapNavigation($app)
    {
        if ($app->user->isGuest) {
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
            'modelName' => 'Page',
            'controller' => 'backend',
        ]);
    }
}