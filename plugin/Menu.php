<?php
/**
 */

namespace execut\pages\plugin;


use execut\navigation\Page;
use execut\navigation\page\NotFound;
use execut\pages\Plugin;
use execut\pages\plugin\crudFields\MenuPlugin;
use yii\db\ActiveQuery;
use yii\db\Expression;

class Menu implements Plugin
{
    public function getPageFieldsPlugins()
    {
        return [
            'createMenu' => [
                'class' => MenuPlugin::class,
            ],
        ];
    }

    public function getCacheKeyQueries() {
        return [
            'item' => \execut\menu\models\Item::find()
                ->where('visible')
                ->select([
                    'key' => new Expression('COALESCE(updated,created)')
                ])
                ->orderBy(new Expression('COALESCE(updated,created) DESC'))
                ->limit(1)
        ];
    }

    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel) {
    }

    public function applyCurrentPageScopes(ActiveQuery $q)
    {
        return $q;
    }

   public function configureErrorPage(NotFound $notFoundPage, \Exception $e) {
   }
}