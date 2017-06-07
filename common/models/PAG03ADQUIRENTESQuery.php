<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[PAG03ADQUIRENTES]].
 *
 * @see PAG03ADQUIRENTES
 */
class PAG03ADQUIRENTESQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PAG03ADQUIRENTES[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PAG03ADQUIRENTES|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}