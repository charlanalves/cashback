<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB13FOTOEMPRESA]].
 *
 * @see CB13FOTOEMPRESA
 */
class CB13FOTOEMPRESAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB13FOTOEMPRESA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB13FOTOEMPRESA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
