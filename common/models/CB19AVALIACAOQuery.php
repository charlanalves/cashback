<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB19AVALIACAO]].
 *
 * @see CB19AVALIACAO
 */
class CB19AVALIACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CB19AVALIACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB19AVALIACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}