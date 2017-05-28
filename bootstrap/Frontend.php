<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\navigation\Component;
use execut\pages\models\Page;
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
                'sitemap' => [
                    'class' => \assayerpro\sitemap\Module::className(),
                    'components' => [
                        'generator' => [
                            'class' => 'assayerpro\sitemap\Generator',
                            'models' => [
                                // or configuration for creating a behavior
                                [
                                    'class' => Page::className(),
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
                                                    'changefreq' => \assayerpro\sitemap\Generator::DAILY,
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
                ],
            ],
        ];
    }
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $app->defaultRoute = 'pages/frontend';
        $app->getErrorHandler()->errorAction = 'pages/frontend/error';
        $app->urlManager->enablePrettyUrl = true;
        $navigation = \yii::$app->navigation;
        $navigation->addConfigurator([
            'class' => Configurator::class,
        ]);
        $app->urlManager->addRules([
            'sitemap.xml' => [
                'pattern' => 'sitemap',
                'route' => 'sitemap/web/index',
                'suffix' => '.xml',
            ],
        ]);
    }
}