<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB15LIKEEMPRESA]].
 *
 * @see CB15LIKEEMPRESA
 */
class CB15LIKEEMPRESAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB15LIKEEMPRESA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB15LIKEEMPRESA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
