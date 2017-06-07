<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB08FORMAPAGAMENTO]].
 *
 * @see CB08FORMAPAGAMENTO
 */
class CB08FORMAPAGAMENTOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB08FORMAPAGAMENTO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB08FORMAPAGAMENTO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}