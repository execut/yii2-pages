<?php
/**
 */

namespace execut\pages;


class MigrationHelper
{
    public $table = null;
    public function attach() {
        $this->table
            ->addForeignColumn('pages_pages', false, null, 'pages_page_id', null, 'id')
            ->createIndex('pages_page_id');
    }
}