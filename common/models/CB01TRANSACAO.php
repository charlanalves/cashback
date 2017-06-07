<?php

namespace common\models;

use Yii;
use common\models\base\CB01TRANSACAO as BaseCB01TRANSACAO;

/**
 * This is the model class for table "CB01_TRANSACAO".
 */
class CB01TRANSACAO extends BaseCB01TRANSACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB01_CLIENTE_ID', 'CB01_EMPRESA_ID', 'CB01_VALOR_COMPRA', 'CB01_VALOR_DEVOLTA'], 'required'],
            [['CB01_CLIENTE_ID', 'CB01_EMPRESA_ID', 'CB01_STATUS'], 'integer'],
            [['CB01_DT_COMPRA'], 'safe'],
            [['CB01_VALOR_COMPRA', 'CB01_VALOR_DEVOLTA'], 'number'],
            
            
        ]);
    }
	
}
