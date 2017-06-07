<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[PAG02ITEMTRANSACAO]].
 *
 * @see PAG02ITEMTRANSACAO
 */
class PAG02ITEMTRANSACAOQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PAG02ITEMTRANSACAO[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PAG02ITEMTRANSACAO|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}