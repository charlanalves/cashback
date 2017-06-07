<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB13_FOTO_EMPRESA".
 *
 * @property integer $CB13_ID
 * @property integer $CB13_EMPRESA_ID
 * @property integer $CB13_CAMPA
 * @property string $CB13_URL
 *
 * @property common\models\CB04EMPRESA $cB13EMPRESA
 */
class CB13FOTOEMPRESA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB13_EMPRESA_ID', 'CB13_CAMPA', 'CB13_URL'], 'required'],
            [['CB13_EMPRESA_ID', 'CB13_CAMPA'], 'integer'],
            [['CB13_URL'], 'string', 'max' => 50],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB13_ID' => 'Cb13  ID',
            'CB13_EMPRESA_ID' => 'Cb13  Empresa  ID',
            'CB13_CAMPA' => 'Cb13  Campa',
            'CB13_URL' => 'Cb13  Url',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB13EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB13_EMPRESA_ID']);
    }
    

}
