<?php
/**
 */

namespace execut\pages\action\adapter;


use execut\actions\action\Adapter;

class ShowPage extends Adapter
{
    public $modelClass = null;
    protected function _run()
    {
        $class = $this->modelClass;
        $model = $class::findById($this->actionParams->get['id']);
        return $this->getResponse([
            'content' => [
                'model' => $model
            ],
        ]);
    }
}