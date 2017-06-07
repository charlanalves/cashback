<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB03_CONTA_BANC".
 *
 * @property integer $CB03_ID
 * @property string $CB03_COD_BANCO
 * @property integer $CB03_TP_CONTA
 * @property integer $CB03_NUM_CONTA
 * @property string $CB03_AGENCIA
 * @property integer $CB03_STATUS
 * @property integer $CB03_CLIENTE_ID
 * @property string $CB03_VALOR
 * @property string $CB03_SAQUE_MIN
 * @property string $CB03_SAQUE_MAX
 *
 * @property common\models\User $cB03CLIENTE
 */
class CB03CONTABANC extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_STATUS', 'CB03_CLIENTE_ID', 'CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'required'],
            [['CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'integer'],
            [['CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'number'],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
            [['CB03_AGENCIA'], 'string', 'max' => 5],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB03_ID' => 'Cb03  ID',
            'CB03_COD_BANCO' => 'Cb03  Cod  Banco',
            'CB03_TP_CONTA' => 'Cb03  Tp  Conta',
            'CB03_NUM_CONTA' => 'Cb03  Num  Conta',
            'CB03_AGENCIA' => 'Cb03  Agencia',
            'CB03_STATUS' => 'Cb03  Status',
            'CB03_CLIENTE_ID' => 'Cb03  Cliente  ID',
            'CB03_VALOR' => 'Cb03  Valor',
            'CB03_SAQUE_MIN' => 'Cb03  Saque  Min',
            'CB03_SAQUE_MAX' => 'Cb03  Saque  Max',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB03CLIENTE()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB03_CLIENTE_ID']);
    }
    

}
