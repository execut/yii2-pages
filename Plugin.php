<?php
/**
 */

namespace execut\pages;


use execut\navigation\Page;
use execut\navigation\page\NotFound;
use yii\base\Exception;
use yii\db\ActiveQuery;

interface Plugin
{
    public function getPageFieldsPlugins();
    public function getCacheKeyQueries();
    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel);
    public function applyCurrentPageScopes(ActiveQuery $q);
    public function configureErrorPage(NotFound $notFoundPage, Exception $e);
}