<?php


namespace execut\pages\crudFields\pageAddress\adapter;


use execut\pages\crudFields\pageAddress\Adapter;
use yii\helpers\Url;
use yii\web\UrlRule;

class Simple implements Adapter
{
    public function getLabel(): string
    {
        return \yii::t('execut/pages', 'Simple');
    }

    public function getFields():array {
        return [];
    }

    public function getKey(): int
    {
        return 1;
    }

    public function toArray($address)
    {
        preg_match('/^\/pages\/frontend\/index\?id=(\d+)$/', $address, $matches);
        if (!empty($matches[1])) {
            return [
                'id' => $matches[1],
            ];
        }
    }

    public function toString($attributes)
    {
        if (empty($attributes['id'])) {
            return;
        }

        return '/pages/frontend/index?id=' . $attributes['id'];
    }
}