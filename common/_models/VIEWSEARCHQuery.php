<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[VIEWSEARCH]].
 *
 * @see VIEWSEARCH
 */
class VIEWSEARCHQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VIEWSEARCH[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VIEWSEARCH|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
