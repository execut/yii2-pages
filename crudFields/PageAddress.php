<?php


namespace execut\pages\crudFields;


use execut\crudFields\fields\Field;
use execut\pages\crudFields\pageAddress\Adapter;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PageAddress extends Field
{
    protected function applyFieldScopes(ActiveQueryInterface $query)
    {
        $address = $this->getValue();
        if (!empty($address)) {
            $this->findPageByAddress($query);
        }
    }

    protected function findPageByAddress($query) {
        $address = $this->getValue();
        $adapter = $this->model->getAdapter();
        if (!$adapter) {
            return;
        }

        $params = $adapter->toArray($address);
        if ($params === false) {
            return $query->andWhere('true=false');
        }

        return $query->andWhere($params);
    }

    protected function getPreparedRules():array
    {
        return ArrayHelper::merge(parent::getPreparedRules(), [
            'requiredTypeForPageAddress' => [
                ['type'], 'required', 'on' => Field::SCENARIO_GRID, 'when' => function () {
                    $attribute = $this->attribute;
                    if (empty($this->model->$attribute)) {
                        return false;
                    }

                    return true;
                }
            ]
        ]);
    }

    public function getColumn()
    {
        return ArrayHelper::merge(parent::getColumn(), [
            'value' => function ($row) {
                $link = $row->getAdapter()->toString(array_filter($row->attributes));
                return Html::a($link, $link, [
                    'target' => '_blank',
                ]);
            },
            'format' => 'raw',
        ]);
    }
}