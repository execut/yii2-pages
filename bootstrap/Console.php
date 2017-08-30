<?php
/**
 */

namespace execut\pages\bootstrap;


use execut\pages\controllers\ConsoleController;
use execut\yii\Bootstrap;

class Console extends Bootstrap
{
    public function bootstrap($app)
    {
        parent::bootstrap($app); // TODO: Change the autogenerated stub
        if (empty($app->controllerMap['pages'])) {
            $app->controllerMap['pages'] = [
                'class' => ConsoleController::class
            ];
        }

        $urlManagerParams = [
            'scriptUrl' => '/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => '/',
        ];
        foreach ($urlManagerParams as $urlManagerParam => $value) {
            \yii::$app->urlManager->$urlManagerParam = $value;
        }
    }
}