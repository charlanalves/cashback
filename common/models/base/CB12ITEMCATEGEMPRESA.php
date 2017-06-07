<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB12_ITEM_CATEG_EMPRESA".
 *
 * @property integer $CB12_ID
 * @property integer $CB12_ITEM_ID
 * @property integer $CB12_EMPRESA_ID
 * @property integer $CB12_PRODUTO_ID
 *
 * @property common\models\CB11ITEMCATEGORIA $cB12ITEM
 * @property common\models\CB04EMPRESA $cB12EMPRESA
 * @property common\models\CB05PRODUTO $cB12PRODUTO
 */
class CB12ITEMCATEGEMPRESA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB12_ITEM_ID'], 'required'],
            [['CB12_ITEM_ID', 'CB12_EMPRESA_ID', 'CB12_PRODUTO_ID'], 'integer'],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB12_ID' => 'Cb12  ID',
            'CB12_ITEM_ID' => 'Cb12  Item  ID',
            'CB12_EMPRESA_ID' => 'Cb12  Empresa  ID',
            'CB12_PRODUTO_ID' => 'Cb12  Produto  ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEM()
    {
        return $this->hasOne(\common\models\CB11ITEMCATEGORIA::className(), ['CB11_ID' => 'CB12_ITEM_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB12_EMPRESA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12PRODUTO()
    {
        return $this->hasOne(\common\models\CB05PRODUTO::className(), ['CB05_ID' => 'CB12_PRODUTO_ID']);
    }
    

}
