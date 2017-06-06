<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB07CASHBACK]].
 *
 * @see CB07CASHBACK
 */
class CB07CASHBACKQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB07CASHBACK[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB07CASHBACK|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}