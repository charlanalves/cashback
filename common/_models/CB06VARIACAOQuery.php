<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB06VARIACAO]].
 *
 * @see CB06VARIACAO
 */
class CB06VARIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB06VARIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB06VARIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
