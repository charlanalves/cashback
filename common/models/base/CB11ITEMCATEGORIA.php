<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB11_ITEM_CATEGORIA".
 *
 * @property integer $CB11_ID
 * @property integer $CB11_CATEGORIA_ID
 * @property string $CB11_DESCRICAO
 * @property integer $CB11_STATUS
 *
 * @property common\models\CB10CATEGORIA $cB11CATEGORIA
 * @property common\models\CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 */
class CB11ITEMCATEGORIA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB11_CATEGORIA_ID', 'CB11_DESCRICAO'], 'required'],
            [['CB11_CATEGORIA_ID', 'CB11_STATUS'], 'integer'],
            [['CB11_DESCRICAO'], 'string', 'max' => 30],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB11_ID' => 'Cb11  ID',
            'CB11_CATEGORIA_ID' => 'Cb11  Categoria  ID',
            'CB11_DESCRICAO' => 'Cb11  Descricao',
            'CB11_STATUS' => 'Cb11  Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB11CATEGORIA()
    {
        return $this->hasOne(\common\models\CB10CATEGORIA::className(), ['CB10_ID' => 'CB11_CATEGORIA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(\common\models\CB12ITEMCATEGEMPRESA::className(), ['CB12_ITEM_ID' => 'CB11_ID']);
    }
    

}
