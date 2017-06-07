<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB14FOTOPRODUTO]].
 *
 * @see CB14FOTOPRODUTO
 */
class CB14FOTOPRODUTOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB14FOTOPRODUTO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB14FOTOPRODUTO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}