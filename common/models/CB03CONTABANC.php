<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB03_CONTA_BANC".
 *
 * @property integer $CB03_ID
 * @property integer $CB03_COD_BANCO
 * @property integer $CB03_TP_CONTA
 * @property integer $CB03_NUM_CONTA
 * @property string $CB03_AGENCIA
 * @property integer $CB03_STATUS
 * @property integer $CB03_CLIENTE_ID
 *
 * @property CB02CLIENTE $cB03CLIENTE
 */
class CB03CONTABANC extends \common\models\GlobalModel
{
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
            [['CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'required'],
            [['CB03_COD_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_STATUS', 'CB03_CLIENTE_ID'], 'integer'],
            [['CB03_AGENCIA'], 'string', 'max' => 5],
            [['CB03_CLIENTE_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB02CLIENTE::className(), 'targetAttribute' => ['CB03_CLIENTE_ID' => 'CB02_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB03_ID' => Yii::t('app', 'Cb03  ID'),
            'CB03_COD_BANCO' => Yii::t('app', 'Cb03  Cod  Banco'),
            'CB03_TP_CONTA' => Yii::t('app', 'Cb03  Tp  Conta'),
            'CB03_NUM_CONTA' => Yii::t('app', 'Cb03  Num  Conta'),
            'CB03_AGENCIA' => Yii::t('app', 'Cb03  Agencia'),
            'CB03_STATUS' => Yii::t('app', 'Cb03  Status'),
            'CB03_CLIENTE_ID' => Yii::t('app', 'Cb03  Cliente  ID'),
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
