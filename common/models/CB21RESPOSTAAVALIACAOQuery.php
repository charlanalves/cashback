<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB21RESPOSTAAVALIACAO]].
 *
 * @see CB21RESPOSTAAVALIACAO
 */
class CB21RESPOSTAAVALIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB21RESPOSTAAVALIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB21RESPOSTAAVALIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}