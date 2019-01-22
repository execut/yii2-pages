<?php
/**
 */

namespace execut\pages;

use execut\dependencies\PluginBehavior;
use execut\navigation\Page;
use execut\navigation\page\NotFound;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module implements Plugin
{
    public $indexViewPath = null;
    public $adminRole = '@';
    public $isCacheEnabled = true;
    public $notFoundPage = [];
    public function behaviors()
    {
        return [
            [
                'class' => PluginBehavior::class,
                'pluginInterface' => Plugin::class,
            ],
        ];
    }

    public function getPageFieldsPlugins() {
        $results = $this->getPluginsResults(__FUNCTION__);
        if (!$results) {
            $results = [];
        }

        return $results;
    }

    public function getCacheKeyQueries() {
        return $this->getPluginsResults(__FUNCTION__);
    }

    public function initCurrentNavigationPage(Page $navigationPage, \execut\pages\models\Page $pageModel) {
        return $this->getPluginsResults(__FUNCTION__, false, [$navigationPage, $pageModel]);
    }

    public function getLastModificationTime() {
        $query = \execut\pages\models\Page::find()
            ->where('visible')
            ->select(['key' => new Expression('COALESCE(updated,created)')])
            ->orderBy(new Expression('COALESCE(updated,created) DESC'))
            ->limit(1);
        $otherQueries = $this->getCacheKeyQueries();
        foreach ($otherQueries as $otherQuery) {
            $query->union($otherQuery);
        }

        $sql = '(' . $query
                ->createCommand()
                ->rawSql . ') ORDER BY key DESC LIMIT 1';

        $time = $this->getModificationTime($sql);

        return $time;
    }

    public function getModificationTime($sql) {
        $time = \yii::$app->db->createCommand($sql)->queryScalar();
        $time = \DateTime::createFromFormat('Y-m-d H:i:s', $time)->getTimestamp();

        return $time;
    }

    public function applyCurrentPageScopes(ActiveQuery $q)
    {
        return $this->getPluginsResults(__FUNCTION__, false, [$q]);
    }

    public function configureErrorPage(NotFound $notFoundPage, \Exception $e)
    {
        foreach ($this->getPlugins() as $key => $plugin) {
            $result = $plugin->configureErrorPage($notFoundPage, $e);
            if ($result) {
                $notFoundPage = $result;
            }
        }

        return $notFoundPage;
    }
}