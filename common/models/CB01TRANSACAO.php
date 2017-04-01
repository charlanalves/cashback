<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB01_TRANSACAO".
 *
 * @property integer $CB01_ID
 * @property integer $CB01_CLIENTE_ID
 * @property integer $CB01_EMPRESA_ID
 * @property string $CB01_DT_COMPRA
 * @property integer $CB01_STATUS
 * @property string $CB01_VALOR_COMPRA
 * @property string $CB01_VALOR_DEVOLTA
 *
 * @property CB02CLIENTE $cB01CLIENTE
 * @property CB04EMPRESA $cB01EMPRESA
 */
class CB01TRANSACAO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB01_TRANSACAO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB01_CLIENTE_ID', 'CB01_EMPRESA_ID', 'CB01_VALOR_COMPRA', 'CB01_VALOR_DEVOLTA'], 'required'],
            [['CB01_CLIENTE_ID', 'CB01_EMPRESA_ID', 'CB01_STATUS'], 'integer'],
            [['CB01_DT_COMPRA'], 'safe'],
            [['CB01_VALOR_COMPRA', 'CB01_VALOR_DEVOLTA'], 'number'],
            [['CB01_CLIENTE_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB02CLIENTE::className(), 'targetAttribute' => ['CB01_CLIENTE_ID' => 'CB02_ID']],
            [['CB01_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB01_EMPRESA_ID' => 'CB04_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB01_ID' => Yii::t('app', 'Cb01  ID'),
            'CB01_CLIENTE_ID' => Yii::t('app', 'Cb01  Cliente  ID'),
            'CB01_EMPRESA_ID' => Yii::t('app', 'Cb01  Empresa  ID'),
            'CB01_DT_COMPRA' => Yii::t('app', 'Cb01  Dt  Compra'),
            'CB01_STATUS' => Yii::t('app', 'Cb01  Status'),
            'CB01_VALOR_COMPRA' => Yii::t('app', 'Cb01  Valor  Compra'),
            'CB01_VALOR_DEVOLTA' => Yii::t('app', 'Cb01  Valor  Devolta'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01CLIENTE()
    {
        return $this->hasOne(CB02CLIENTE::className(), ['CB02_ID' => 'CB01_CLIENTE_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB01_EMPRESA_ID']);
    }

    /**
     * @inheritdoc
     * @return CB01TRANSACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB01TRANSACAOQuery(get_called_class());
    }
}
