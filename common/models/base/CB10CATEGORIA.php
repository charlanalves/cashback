<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB10_CATEGORIA".
 *
 * @property integer $CB10_ID
 * @property string $CB10_NOME
 * @property integer $CB10_STATUS
 *
 * @property common\models\CB04EMPRESA[] $cB04EMPRESAs
 * @property common\models\CB11ITEMCATEGORIA[] $cB11ITEMCATEGORIAs
 */
class CB10CATEGORIA extends \common\models\GlobalModel
{
 

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
    public static function tableName()
    {
        return 'CB10_CATEGORIA';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB10_ID' => 'Cb10  ID',
            'CB10_NOME' => 'Cb10  Nome',
            'CB10_STATUS' => 'Cb10  Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB04EMPRESAs()
    {
        return $this->hasMany(\common\models\CB04EMPRESA::className(), ['CB04_CATEGORIA_ID' => 'CB10_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB11ITEMCATEGORIAs()
    {
        return $this->hasMany(\common\models\CB11ITEMCATEGORIA::className(), ['CB11_CATEGORIA_ID' => 'CB10_ID']);
    }
    

}
