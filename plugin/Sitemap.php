<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 9/20/18
 * Time: 5:01 PM
 */

namespace execut\pages\plugin;


use execut\navigation\Page;
use execut\navigation\page\NotFound;
use execut\pages\Plugin;
use yii\base\Exception;
use yii\db\ActiveQuery;

class Sitemap implements Plugin
{
    public function getPageFieldsPlugins() {
        return [
            'sitemap' => [
                'class' => \execut\sitemap\crudFields\Plugin::class,
            ],
        ];
    }

    public function getCacheKeyQueries() {}
    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel) {}
    public function applyCurrentPageScopes(ActiveQuery $q) {}
    public function configureErrorPage(NotFound $notFoundPage, \Exception $e) {}

    public function getPageAddressAdapters(): array
    {
        return [];
    }
}