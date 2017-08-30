<?php
/**
 */

namespace execut\pages\crudFields;
use execut\crudFields\fields\HasOneSelect2;
use execut\pages\models\Page;

class Plugin extends \execut\crudFields\Plugin
{
    public function getFields() {
        return [
            [
                'class' => HasOneSelect2::class,
                'attribute' => 'pages_page_id',
                'relation' => 'pagesPage',
                'url' => [
                    '/pages/backend'
                ],
            ],
        ];
    }

    public function getRelations()
    {
        return [
            'pagesPage' => [
                'class' => Page::class,
                'name' => 'pagesPage',
                'link' => [
                    'id' => 'pages_page_id',
                ],
                'multiple' => false
            ],
        ];
    }
}