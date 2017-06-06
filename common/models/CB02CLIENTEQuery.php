<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB02CLIENTE]].
 *
 * @see CB02CLIENTE
 */
class CB02CLIENTEQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB02CLIENTE[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB02CLIENTE|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}