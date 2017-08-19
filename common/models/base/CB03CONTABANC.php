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
 * @property integer $CB03_USER_ID
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
           [['CB03_NOME_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_USER_ID', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'required'],
            [['CB03_TP_CONTA', 'CB03_STATUS', 'CB03_USER_ID'], 'integer'],
            [['CB03_VALOR', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'number'],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
            [['CB03_NOME_BANCO'], 'string', 'max' => 50],
            
            
            
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
            'CB03_ID' => Yii::t('app', 'ID'),
            'CB03_COD_BANCO' => Yii::t('app', 'Código Banco'),
            'CB03_NOME_BANCO' => Yii::t('app', 'Banco'),
            'CB03_TP_CONTA' => Yii::t('app', 'Tipo da conta'),
            'CB03_NUM_CONTA' => Yii::t('app', 'Número da conta'),
            'CB03_AGENCIA' => Yii::t('app', 'Agência'),
            'CB03_STATUS' => Yii::t('app', 'Status'),
            'CB03_USER_ID' => Yii::t('app', 'Cliente'),
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
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB03_USER_ID']);
    }
    

}
