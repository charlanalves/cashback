<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB17PRODUTOPEDIDO]].
 *
 * @see CB17PRODUTOPEDIDO
 */
class CB17PRODUTOPEDIDOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB17PRODUTOPEDIDO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB17PRODUTOPEDIDO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}