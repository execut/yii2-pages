<?php
namespace execut\pages\migrations;

use execut\yii\migration\Migration;
use yii\db\pgsql\Schema;

class m170401_190746_createBaseTable extends Migration
{
    public function initInverter(\execut\yii\migration\Inverter $i)
    {
        $i->createTable('pages_pages', $this->defaultColumns([
            'name' => $this->string()->notNull(),
            'visible' => $this->boolean()->notNull()->defaultValue(true),
        ]));
        $pages = $i->table('pages_pages');
        $pages->addForeignColumn('pages_pages');
    }
}