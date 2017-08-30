<?php
/**
 */

namespace execut\pages\controllers;


use execut\pages\models\Page;
use yii\console\Controller;
use yii\helpers\Url;

class ConsoleController extends Controller
{
    public function actionRecache($useCache = true) {
        $cache = \yii::$app->cache;
        $cacheKey = __CLASS__ . 'lastModificationTime';
        $cachedTime = $cache->get($cacheKey);
        $lastTime = \yii::$app->getModule('pages')->getLastModificationTime();
        if (!$useCache || $cachedTime !== $lastTime) {
            $pages = Page::find()->isVisible()->all();
            foreach ($pages as $page) {
                $url = $page->getUrl();
                $url = Url::to($url, true);
                echo 'opening ' . $url . "\r\n";
                $ch = curl_init($url);
                ob_start();
                curl_exec($ch);
                ob_end_clean();
                curl_close($ch);
            }

            $cache->set($cacheKey, $lastTime);
        }
    }
}