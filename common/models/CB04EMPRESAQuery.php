<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB04EMPRESA]].
 *
 * @see CB04EMPRESA
 */
class CB04EMPRESAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB04EMPRESA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB04EMPRESA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
