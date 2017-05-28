<?php
/**
 */

namespace execut\pages\action\adapter;


use execut\actions\action\Adapter;
use execut\pages\models\FrontendPage;
use yii\web\NotFoundHttpException;

class ShowPage extends Adapter
{
//    public $modelClass = null;
    protected function _run()
    {
//        $class = $this->modelClass;
//        $class::initNavigationPagesById($this->actionParams->get['id']);
        $pageId = \yii::$app->request->getQueryParam('id');
        if ($pageId) {
            $pages = FrontendPage::getNavigationPages($pageId);
            if (empty($pages)) {
                throw new NotFoundHttpException();
            }
        }

        return $this->getResponse([
            'content' => [],
        ]);
    }
}