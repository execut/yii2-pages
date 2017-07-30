<?php
/**
 */

namespace execut\pages\crudFields;
use execut\crudFields\fields\HasOneSelect2;

class Plugin extends \execut\crudFields\Plugin
{
    public function getFields() {
        return [
            [
                'class' => HasOneSelect2::class,
                'attribute' => 'pages_page_id',
                'url' => [
                    '/pages/backend'
                ],
            ],
        ];
    }
}