<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB16PEDIDO]].
 *
 * @see CB16PEDIDO
 */
class CB16PEDIDOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB16PEDIDO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB16PEDIDO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}