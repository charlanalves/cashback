<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB03CONTABANC]].
 *
 * @see CB03CONTABANC
 */
class CB03CONTABANCQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB03CONTABANC[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB03CONTABANC|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}