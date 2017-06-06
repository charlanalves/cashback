<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[VIEWEXTRATOCLIENTE]].
 *
 * @see VIEWEXTRATOCLIENTE
 */
class VIEWEXTRATOCLIENTEQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VIEWEXTRATOCLIENTE[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VIEWEXTRATOCLIENTE|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}