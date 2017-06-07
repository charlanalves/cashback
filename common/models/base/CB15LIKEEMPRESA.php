<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB15_LIKE_EMPRESA".
 *
 * @property integer $CB15_EMPRESA_ID
 * @property integer $CB15_USER_ID
 *
 * @property common\models\CB04EMPRESA $cB15EMPRESA
 * @property common\models\User $cB15USER
 */
class CB15LIKEEMPRESA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'required'],
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'integer'],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB15_LIKE_EMPRESA';
    }

 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB15_EMPRESA_ID' => 'Cb15  Empresa  ID',
            'CB15_USER_ID' => 'Cb15  User  ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB15_EMPRESA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15USER()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB15_USER_ID']);
    }
    

}
