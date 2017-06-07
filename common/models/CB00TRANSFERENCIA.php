<?php

namespace common\models;

use Yii;
use common\models\base\CB00TRANSFERENCIA as BaseCB00TRANSFERENCIA;

/**
 * This is the model class for table "CB00_TRANSFERENCIA".
 */
class CB00TRANSFERENCIA extends BaseCB00TRANSFERENCIA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB00_CLIENTE_ID', 'CB00_COD_BANCO', 'CB00_TP_CONTA', 'CB00_NUM_CONTA', 'CB00_AGENCIA', 'CB00_STATUS', 'CB00_VALOR_TRANSFERIDO'], 'required'],
            [['CB00_CLIENTE_ID', 'CB00_COD_BANCO', 'CB00_TP_CONTA', 'CB00_NUM_CONTA', 'CB00_STATUS'], 'integer'],
            [['CB00_DT_SOLICITACAO', 'CB00_DT_CONCLUSAO'], 'safe'],
            [['CB00_VALOR_TRANSFERIDO'], 'number'],
            [['CB00_AGENCIA'], 'string', 'max' => 5],
            
            
        ]);
    }
	
}
