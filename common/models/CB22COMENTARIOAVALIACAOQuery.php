<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB22COMENTARIOAVALIACAO]].
 *
 * @see CB22COMENTARIOAVALIACAO
 */
class CB22COMENTARIOAVALIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB22COMENTARIOAVALIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB22COMENTARIOAVALIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}