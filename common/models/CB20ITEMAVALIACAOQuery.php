<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB20ITEMAVALIACAO]].
 *
 * @see CB20ITEMAVALIACAO
 */
class CB20ITEMAVALIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB20ITEMAVALIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB20ITEMAVALIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}