<?php

namespace common\models;

use Yii;
use common\models\base\CB06VARIACAO as BaseCB06VARIACAO;

/**
 * This is the model class for table "CB06_VARIACAO".
 */
class CB06VARIACAO extends BaseCB06VARIACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB06_PRODUTO_ID', 'CB06_TITULO', 'CB06_DESCRICAO', 'CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'required'],
            [['CB06_PRODUTO_ID'], 'integer'],
            [['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
            
            
        ]);
    }
	
}
