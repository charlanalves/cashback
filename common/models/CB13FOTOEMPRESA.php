<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB13_FOTO_EMPRESA".
 *
 * @property integer $CB13_ID
 * @property integer $CB13_EMPRESA_ID
 * @property integer $CB13_CAMPA
 * @property string $CB13_URL
 *
 * @property CB04EMPRESA $cB13EMPRESA
 */
class CB13FOTOEMPRESA extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB13_FOTO_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB13_EMPRESA_ID', 'CB13_CAMPA', 'CB13_URL'], 'required'],
            [['CB13_EMPRESA_ID', 'CB13_CAMPA'], 'integer'],
            [['CB13_URL'], 'string', 'max' => 50],
            [['CB13_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB13_EMPRESA_ID' => 'CB04_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB13_ID' => Yii::t('app', 'Cb13  ID'),
            'CB13_EMPRESA_ID' => Yii::t('app', 'Cb13  Empresa  ID'),
            'CB13_CAMPA' => Yii::t('app', 'Cb13  Campa'),
            'CB13_URL' => Yii::t('app', 'Cb13  Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB13EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB13_EMPRESA_ID']);
    }

    /**
     * @inheritdoc
     * @return CB13FOTOEMPRESAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB13FOTOEMPRESAQuery(get_called_class());
    }
}
