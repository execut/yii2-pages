<?php
/**
 */

namespace execut\pages;


use execut\navigation\Page;

interface Plugin
{
    public function getPageFieldsPlugins();
    public function getCacheKeyQueries();
    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel);
}