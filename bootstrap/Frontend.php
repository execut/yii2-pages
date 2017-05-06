<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\navigation\Component;
use execut\pages\Module;
use execut\pages\navigation\Configurator;
use execut\yii\Bootstrap;
use yii\base\BootstrapInterface;

class Frontend extends Bootstrap
{
    public function getDefaultDepends()
    {
        return [
            'components' => [
                'navigation' => [
                    'class' => \execut\navigation\Component::class,
                ],
            ],
            'modules' => [
                'pages' => [
                    'class' => Module::class,
                ],
            ],
        ];
    }
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->defaultRoute = 'pages/frontend';
        $app->getErrorHandler()->errorAction = 'pages/frontend/error';
        $app->urlManager->enablePrettyUrl = true;
        $navigation = \yii::$app->navigation;
        $navigation->addConfigurator([
            'class' => Configurator::class,
        ]);
//        $app->urlManager->addRules([
//            [
//                'route' => '',
//                'pattern' => 'pages/frontend',
//            ],
//        ]);
    }
}