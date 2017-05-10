<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB12_ITEM_CATEG_EMPRESA".
 *
 * @property integer $CB12_ID
 * @property integer $CB12_ITEM_ID
 * @property integer $CB12_EMPRESA_ID
 * @property integer $CB12_PRODUTO_ID
 *
 * @property CB11ITEMCATEGORIA $cB12ITEM
 * @property CB04EMPRESA $cB12EMPRESA
 * @property CB05PRODUTO $cB12PRODUTO
 */
class CB12ITEMCATEGEMPRESA extends \common\models\GlobalModel
{
    
    public $ITEM;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB12_ITEM_CATEG_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ITEM'], 'safe'],
            [['CB12_ITEM_ID', 'CB12_EMPRESA_ID', 'CB12_PRODUTO_ID'], 'integer'],
            [['CB12_ITEM_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB11ITEMCATEGORIA::className(), 'targetAttribute' => ['CB12_ITEM_ID' => 'CB11_ID']],
            [['CB12_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB12_EMPRESA_ID' => 'CB04_ID']],
            [['CB12_PRODUTO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB05PRODUTO::className(), 'targetAttribute' => ['CB12_PRODUTO_ID' => 'CB05_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB12_ID' => Yii::t('app', 'Cb12  ID'),
            'CB12_ITEM_ID' => Yii::t('app', 'Cb12  Item  ID'),
            'CB12_EMPRESA_ID' => Yii::t('app', 'Cb12  Empresa  ID'),
            'CB12_PRODUTO_ID' => Yii::t('app', 'Cb12  Produto  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEM()
    {
        return $this->hasOne(CB11ITEMCATEGORIA::className(), ['CB11_ID' => 'CB12_ITEM_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB12_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12PRODUTO()
    {
        return $this->hasOne(CB05PRODUTO::className(), ['CB05_ID' => 'CB12_PRODUTO_ID']);
    }

    /**
     * @inheritdoc
     * @return CB12ITEMCATEGEMPRESAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB12ITEMCATEGEMPRESAQuery(get_called_class());
    }
}
