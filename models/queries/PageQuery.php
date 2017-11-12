<?php

namespace execut\pages\models\queries;

/**
 * This is the ActiveQuery class for [[\execut\pages\models\Page]].
 *
 * @see \execut\pages\models\Page
 */
class PageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \execut\pages\models\Page[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \execut\pages\models\Page|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function isVisible() {
        $modelClass = $this->modelClass;

        return $this->andWhere($modelClass::tableName() . '.visible');
    }

    public function withParents() {
        return $this->generateWith($this);
    }

    protected function generateWith($q, $level = 0) {
        if ($level < 5) {
            if ($level > 0) {
                $q->forLinks();
            }

            return $q->with([
                'pagesPage' => function ($q) use ($level) {
                    $this->generateWith($q,$level + 1);
                }
            ]);
        }
    }

    public function forLinks() {
        $excludedColumns = [
            'title',
            'text',
            'keywords',
        ];
        $modelClass = $this->modelClass;
        $columns = $modelClass::getTableSchema()->columns;
        $select = [];
        foreach ($columns as $column) {
            if (!in_array($column->name, $excludedColumns)) {
                $select[] = $column->name;
            }
        }

        return $this->select($select);
    }
}
