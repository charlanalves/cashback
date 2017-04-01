<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB10_CATEGORIA".
 *
 * @property integer $CB10_ID
 * @property string $CB10_NOME
 * @property integer $CB10_STATUS
 *
 * @property CB04EMPRESA[] $cB04EMPRESAs
 * @property CB11ITEMCATEGORIA[] $cB11ITEMCATEGORIAs
 */
class CB10CATEGORIA extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB10_CATEGORIA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB10_NOME'], 'required'],
            [['CB10_STATUS'], 'integer'],
            [['CB10_NOME'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB10_ID' => Yii::t('app', 'Cb10  ID'),
            'CB10_NOME' => Yii::t('app', 'Cb10  Nome'),
            'CB10_STATUS' => Yii::t('app', 'Cb10  Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB04EMPRESAs()
    {
        return $this->hasMany(CB04EMPRESA::className(), ['CB04_CATEGORIA_ID' => 'CB10_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB11ITEMCATEGORIAs()
    {
        return $this->hasMany(CB11ITEMCATEGORIA::className(), ['CB11_CATEGORIA_ID' => 'CB10_ID']);
    }

    /**
     * @inheritdoc
     * @return CB10CATEGORIAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB10CATEGORIAQuery(get_called_class());
    }
}
