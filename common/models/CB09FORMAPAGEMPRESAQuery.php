<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CB09FORMAPAGEMPRESA]].
 *
 * @see CB09FORMAPAGEMPRESA
 */
class CB09FORMAPAGEMPRESAQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CB09FORMAPAGEMPRESA[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CB09FORMAPAGEMPRESA|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
