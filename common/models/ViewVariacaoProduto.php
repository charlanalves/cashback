<?php

namespace common\models;

use Yii;
use common\models\base\ViewVariacaoProduto as BaseViewVariacaoProduto;

/**
 * This is the model class for table "view_variacao_produto".
 */
class ViewVariacaoProduto extends BaseViewVariacaoProduto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB06_ID', 'CB05_ID'], 'integer'],
            [['CB06_TITULO', 'CB06_DESCRICAO', 'CB06_PRECO'], 'required'],
            [['CB06_PRECO', 'CB07_PERCENTUAL', 'VALOR_CB'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
            
            
        ]);
    }
	
}
