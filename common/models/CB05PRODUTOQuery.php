<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB05PRODUTO]].
 *
 * @see CB05PRODUTO
 */
class CB05PRODUTOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB05PRODUTO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB05PRODUTO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}