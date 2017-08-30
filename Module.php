<?php
/**
 */

namespace execut\pages;

use execut\dependencies\PluginBehavior;
use execut\navigation\Page;
use yii\db\Expression;
use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module implements Plugin
{
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
        return $this->getPluginsResults(__FUNCTION__);
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
        $otherQueries = \yii::$app->getModule('pages')->getCacheKeyQueries();
        foreach ($otherQueries as $otherQuery) {
            $query->union($otherQuery);
        }

        $sql = '(' . $query
                ->createCommand()
                ->rawSql . ') ORDER BY key DESC LIMIT 1';

        $time = $this->getModificationTime($sql);
    }

    public function getModificationTime($sql) {
        $time = \yii::$app->db->createCommand($sql)->queryScalar();
        $time = \DateTime::createFromFormat('Y-m-d H:i:s', $time)->getTimestamp();

        return $time;
    }
}