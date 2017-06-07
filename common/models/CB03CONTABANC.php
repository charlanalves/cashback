<?php

namespace common\models;

use Yii;
use common\models\base\CB03CONTABANC as BaseCB03CONTABANC;

/**
 * This is the model class for table "CB03_CONTA_BANC".
 */
class CB03CONTABANC extends BaseCB03CONTABANC
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_STATUS', 'CB03_CLIENTE_ID', 'CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'required'],
            [['CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'integer'],
            [['CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'number'],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
            [['CB03_AGENCIA'], 'string', 'max' => 5],
            
            
        ]);
    }
	
}
