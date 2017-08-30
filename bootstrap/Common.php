<?php
/**
 */

namespace execut\pages\bootstrap;

use execut\yii\Bootstrap;

class Common extends Bootstrap
{

    public function getDefaultDepends()
    {
        return [
            'bootstrap' => [
                'navigation' => [
                    'class' => \execut\navigation\Bootstrap::class,
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
        parent::bootstrap($app);
        $app->urlManager->showScriptName = false;
        $app->urlManager->enablePrettyUrl = true;
    }
}