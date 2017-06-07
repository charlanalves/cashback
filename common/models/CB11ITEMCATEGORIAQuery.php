<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB11ITEMCATEGORIA]].
 *
 * @see CB11ITEMCATEGORIA
 */
class CB11ITEMCATEGORIAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB11ITEMCATEGORIA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB11ITEMCATEGORIA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}