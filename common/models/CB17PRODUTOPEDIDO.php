<?php

namespace common\models;

use Yii;
use common\models\base\CB17PRODUTOPEDIDO as BaseCB17PRODUTOPEDIDO;

/**
 * This is the model class for table "CB17_PRODUTO_PEDIDO".
 */
class CB17PRODUTOPEDIDO extends BaseCB17PRODUTOPEDIDO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_NOME_PRODUTO', 'CB17_QTD', 'CB17_VLR_UNID'], 'required'],
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_QTD', 'CB17_VARIACAO_ID', 'CB17_AVALIADO'], 'integer'],
            [['CB17_VLR_UNID'], 'number'],
            [['CB17_NOME_PRODUTO'], 'string', 'max' => 100],
            
            
        ]);
    }
	
}
