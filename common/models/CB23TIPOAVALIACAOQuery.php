<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB23TIPOAVALIACAO]].
 *
 * @see CB23TIPOAVALIACAO
 */
class CB23TIPOAVALIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB23TIPOAVALIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB23TIPOAVALIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}