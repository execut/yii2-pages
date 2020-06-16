<?php
namespace execut\pages\migrations;

use execut\yii\migration\Migration;
use execut\yii\migration\Inverter;

class m200609_145950_addTypeField extends Migration
{
    public function initInverter(Inverter $i)
    {
        $table = $i->table('pages_pages')
            ->addColumn('type', $this->integer())
            ->update(['type' => 1])
            ->alterColumnSetNotNull('type');
//        $table->addColumn('is_denied_for_indexation', $this->boolean());
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
