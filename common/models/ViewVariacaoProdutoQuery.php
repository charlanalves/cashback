<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ViewVariacaoProduto]].
 *
 * @see ViewVariacaoProduto
 */
class ViewVariacaoProdutoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ViewVariacaoProduto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ViewVariacaoProduto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}