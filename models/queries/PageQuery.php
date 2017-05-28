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
        return $this->with('pagesPage.pagesPage.pagesPage.pagesPage.pagesPage');
    }
}
