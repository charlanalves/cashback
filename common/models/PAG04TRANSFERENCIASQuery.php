<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[PAG04TRANSFERENCIAS]].
 *
 * @see PAG04TRANSFERENCIAS
 */
class PAG04TRANSFERENCIASQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PAG04TRANSFERENCIAS[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PAG04TRANSFERENCIAS|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}