<?php

use execut\yii\migration\Migration;
use execut\yii\migration\Inverter;

class m170430_210431_attachToModules extends Migration
{
    public function initInverter(Inverter $i)
    {
        $helper = new \execut\pages\MigrationHelper();
        $module = \yii::$app->getModule('pages');
        foreach ($module->getModels() as $model) {
            $helper->table = $this->table($model::tableName());
            $helper->attach();
        }
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
