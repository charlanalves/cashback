<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB03_CONTA_BANC".
 *
 * @property integer $CB03_ID
 * @property string $CB03_COD_BANCO
 * @property integer $CB03_TP_CONTA
 * @property integer $CB03_NUM_CONTA
 * @property string $CB03_AGENCIA
 * @property integer $CB03_STATUS
 * @property integer $CB03_CLIENTE_ID
 * @property integer $CB03_VALOR
 * @property integer $CB03_SAQUE_MIN
 * @property integer $CB03_SAQUE_MAX
 *
 * @property CB02CLIENTE $cB03CLIENTE
 */
class CB03CONTABANC extends \common\models\GlobalModel
{

    const SCENARIO_SAQUE = 'saque';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB03_CONTA_BANC';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_STATUS', 'CB03_CLIENTE_ID', 'CB03_TP_CONTA', 'VALOR'], 'required'],
            [[ 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'integer'],
            [['CB03_AGENCIA'], 'string', 'max' => 5],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB03_ID' => Yii::t('app', 'ID'),
            'CB03_COD_BANCO' => Yii::t('app', 'Banco'),
            'CB03_TP_CONTA' => Yii::t('app', 'Tipo da conta'),
            'CB03_NUM_CONTA' => Yii::t('app', 'Numero da conta'),
            'CB03_AGENCIA' => Yii::t('app', 'Agência'),
            'CB03_STATUS' => Yii::t('app', 'Status'),
            'CB03_CLIENTE_ID' => Yii::t('app', 'Cliente'),
            'CB03_VALOR' => Yii::t('app', 'Valor'),
            'CB03_SAQUE_MIN' => Yii::t('app', 'Saque mínimo'),
            'CB03_SAQUE_MAX' => Yii::t('app', 'Saque máximo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB03CLIENTE()
    {
        return $this->hasOne(CB02CLIENTE::className(), ['CB02_ID' => 'CB03_CLIENTE_ID']);
    }

    /**
     * @inheritdoc
     * @return CB03CONTABANCQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB03CONTABANCQuery(get_called_class());
    }
}
