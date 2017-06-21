<?php

namespace common\models;

use Yii;
use common\models\base\CB03CONTABANC as BaseCB03CONTABANC;

/**
 * This is the model class for table "CB03_CONTA_BANC".
 */
class CB03CONTABANC extends BaseCB03CONTABANC
{

    const SCENARIO_SAQUE = 'saque';
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         	[['CB03_COD_BANCO', 'CB03_NOME_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_STATUS', 'CB03_CLIENTE_ID', 'CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'required'],
            [['CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'integer'],
            [['CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'number'],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
            [['CB03_NOME_BANCO'], 'string', 'max' => 50],
            [['CB03_AGENCIA'], 'string', 'max' => 5],
            [['CB03_CLIENTE_ID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['CB03_CLIENTE_ID' => 'id']],
            [['CB03_VALOR'], 'compare', 'operator' => '>=', 'compareAttribute' => 'CB03_SAQUE_MIN', 'type' => 'number', 'message' => 'Valor mínimo para saque: R$ {compareValue}'],
            [['CB03_VALOR'], 'compare', 'operator' => '<=', 'compareAttribute' => 'CB03_SAQUE_MAX', 'type' => 'number', 'message' => 'O valor informado é maior que seu saldo de R$ {compareValue}'],
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SAQUE] = ['CB03_CLIENTE_ID', 'CB03_AGENCIA', 'CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_VALOR'];
        return $scenarios;        
    }

	
}
