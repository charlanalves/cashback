<?php

namespace common\models;

use Yii;
use common\models\base\CB18VARIACAOPEDIDO as BaseCB18VARIACAOPEDIDO;

/**
 * This is the model class for table "CB18_VARIACAO_PEDIDO".
 */
class CB18VARIACAOPEDIDO extends BaseCB18VARIACAOPEDIDO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB18_ID_VARIACAO', 'CB18_ID_PEDIDO', 'CB18_VLR'], 'required'],
            [['CB18_ID_VARIACAO', 'CB18_ID_PEDIDO'], 'integer'],
            [['CB18_VLR'], 'number'],
            [['CB18_DATA_CRIACAO'], 'safe'],        
        ]);
    }
	
}
