<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SYS01PARAMETROSGLOBAIS]].
 *
 * @see SYS01PARAMETROSGLOBAIS
 */
class SYS01PARAMETROSGLOBAISQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SYS01PARAMETROSGLOBAIS[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SYS01PARAMETROSGLOBAIS|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}