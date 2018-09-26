<?php
/**
 */

namespace execut\pages\plugin;

use execut\navigation\Page;
use yii\db\ActiveQuery;

class Alias implements \execut\pages\Plugin
{
    public function getPageFieldsPlugins() {
        return [
            [
                'class' => \execut\alias\crudFields\Plugin::class,
            ],
        ];
    }

    public function getCacheKeyQueries() {
        return [];
    }

    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel) {
    }

    public function applyCurrentPageScopes(ActiveQuery $q)
    {
        return $q;
    }
}