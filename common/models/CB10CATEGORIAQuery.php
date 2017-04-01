<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB10CATEGORIA]].
 *
 * @see CB10CATEGORIA
 */
class CB10CATEGORIAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB10CATEGORIA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB10CATEGORIA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
