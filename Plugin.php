<?php
/**
 */

namespace execut\pages;


use execut\navigation\Page;
use execut\navigation\page\NotFound;
use yii\db\ActiveQuery;
use yii\db\Query;

interface Plugin
{
    /**
     * Return config of crud fields plugins for pages CRUD
     *
     * @return array|null
     */
    public function getPageFieldsPlugins();

    /**
     * Return queries list for checking pages cache expiration
     *
     * @return Query[]
     */
    public function getCacheKeyQueries();

    /**
     * @param Page $navigationPage
     * @param models\Page $pageModel
     *
     * @return mixed
     */
    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel);
    public function applyCurrentPageScopes(ActiveQuery $q);
    public function configureErrorPage(NotFound $notFoundPage, \Exception $e);
}