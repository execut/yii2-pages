<?php
/**
 */

namespace execut\pages\bootstrap;


use assayerpro\sitemap\Sitemap;
use execut\navigation\Component;
use execut\navigation\configurator\HomePage;
use execut\pages\models\Page;
use execut\pages\Module;
use execut\pages\navigation\Configurator;
use execut\yii\Bootstrap;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Frontend extends Common
{
    public function getDefaultDepends()
    {
        return ArrayHelper::merge(parent::getDefaultDepends(), [
            'bootstrap' => ['pages'],
            'modules' => [
                'sitemap' => [
                    'class' => \assayerpro\sitemap\Module::class,
                ],
            ],
            'components' => [
                'sitemap' => [
                    'class' => Sitemap::class,
                    'models' => [
                        // or configuration for creating a behavior
                        [
                            'class' => Page::class,
                            'behaviors' => [
                                'sitemap' => [
                                    'class' => '\assayerpro\sitemap\behaviors\SitemapBehavior',
                                    'scope' => function ($model) {
                                        $model->isVisible();
                                    },
                                    'dataClosure' => function ($model) {
                                        /** @var self $model */
                                        $date = $model->lastTime;
                                        return [
                                            'loc' => $model->getUrl(),
                                            'lastmod' => $date,
                                            'changefreq' => \assayerpro\sitemap\Sitemap::DAILY,
                                            'priority' => 1
                                        ];
                                    }
                                ],
                            ],
                        ],
                    ],
                    'enableGzip' => false, // default is false
                    'cacheExpire' => 1, // 1 second. Default is 24 hours
                ],
            ],
        ]);
    }
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->defaultRoute = 'pages/frontend/index';
        parent::bootstrap($app);
        $app->getErrorHandler()->errorAction = 'pages/frontend/error';
        $app->urlManager->enablePrettyUrl = true;
        $app->urlManager->addRules([
            ['pattern' => 'sitemap-<id:\d+>', 'route' => '/sitemap/default/index', 'suffix' => '.xml'],
            ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
        ]);
        $navigation = \yii::$app->navigation;

        $navigation->addConfigurator([
            'class' => Configurator::class,
        ]);
    }
}