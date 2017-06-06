<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB01_TRANSACAO".
 *
 * @property integer $CB01_ID
 * @property integer $CB01_CLIENTE_ID
 * @property integer $CB01_EMPRESA_ID
 * @property string $CB01_DT_COMPRA
 * @property integer $CB01_STATUS
 * @property string $CB01_VALOR_COMPRA
 * @property string $CB01_VALOR_DEVOLTA
 *
 * @property common\models\User $cB01CLIENTE
 * @property common\models\CB04EMPRESA $cB01EMPRESA
 */
class CB01TRANSACAO extends \common\models\GlobalModel
{
 

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
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB01_ID' => 'Cb01  ID',
            'CB01_CLIENTE_ID' => 'Cb01  Cliente  ID',
            'CB01_EMPRESA_ID' => 'Cb01  Empresa  ID',
            'CB01_DT_COMPRA' => 'Cb01  Dt  Compra',
            'CB01_STATUS' => 'Cb01  Status',
            'CB01_VALOR_COMPRA' => 'Cb01  Valor  Compra',
            'CB01_VALOR_DEVOLTA' => 'Cb01  Valor  Devolta',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01CLIENTE()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB01_CLIENTE_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB01_EMPRESA_ID']);
    }
    

}
