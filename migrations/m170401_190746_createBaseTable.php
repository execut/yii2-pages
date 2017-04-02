<?php

use execut\yii\migration\Migration;
use execut\seo\MigrationHelper as SeoMigrationHelper;
use execut\alias\MigrationHelper as AliasMigrationHelper;

class m170401_190746_createBaseTable extends Migration
{
    public function initInverter(\execut\yii\migration\Inverter $i)
    {
        $i->createTable('pages_pages', $this->defaultColumns([
            'name' => $this->string()->notNull(),
            'visible' => $this->boolean()->notNull()->defaultValue('true'),
        ]));
        $pages = $i->table('pages_pages')->addForeignColumn('pages_pages');
        $seoColumns = new SeoMigrationHelper([
            'table' => $pages,
        ]);
        $seoColumns->attach();

        $aliasColumns = new AliasMigrationHelper([
            'table' => $pages,
        ]);
        $aliasColumns->attach();
    }
}