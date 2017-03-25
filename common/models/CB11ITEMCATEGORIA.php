<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB11_ITEM_CATEGORIA".
 *
 * @property integer $CB11_ID
 * @property integer $CB11_CATEGORIA_ID
 * @property string $CB11_DESCRICAO
 * @property integer $CB11_STATUS
 *
 * @property CB10CATEGORIA $cB11CATEGORIA
 * @property CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 */
class CB11ITEMCATEGORIA extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB11_ITEM_CATEGORIA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB11_CATEGORIA_ID', 'CB11_DESCRICAO'], 'required'],
            [['CB11_CATEGORIA_ID', 'CB11_STATUS'], 'integer'],
            [['CB11_DESCRICAO'], 'string', 'max' => 30],
            [['CB11_CATEGORIA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB10CATEGORIA::className(), 'targetAttribute' => ['CB11_CATEGORIA_ID' => 'CB10_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB11_ID' => Yii::t('app', 'Cb11  ID'),
            'CB11_CATEGORIA_ID' => Yii::t('app', 'Cb11  Categoria  ID'),
            'CB11_DESCRICAO' => Yii::t('app', 'Cb11  Descricao'),
            'CB11_STATUS' => Yii::t('app', 'Cb11  Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB11CATEGORIA()
    {
        return $this->hasOne(CB10CATEGORIA::className(), ['CB10_ID' => 'CB11_CATEGORIA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(CB12ITEMCATEGEMPRESA::className(), ['CB12_ITEM_ID' => 'CB11_ID']);
    }

    /**
     * @inheritdoc
     * @return CB11ITEMCATEGORIAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB11ITEMCATEGORIAQuery(get_called_class());
    }
}
