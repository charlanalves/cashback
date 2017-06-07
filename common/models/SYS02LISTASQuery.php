<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SYS02LISTAS]].
 *
 * @see SYS02LISTAS
 */
class SYS02LISTASQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SYS02LISTAS[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SYS02LISTAS|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}