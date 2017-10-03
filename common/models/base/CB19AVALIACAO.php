<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "CB19_AVALIACAO".
 *
 * @property integer $CB19_ID
 * @property integer $CB19_EMPRESA_ID
 * @property string $CB19_NOME
 * @property string $CB19_DATA
 * @property integer $CB19_STATUS
 *
 * @property \common\models\CB04EMPRESA $cB19EMPRESA
 * @property \common\models\CB20ITEMAVALIACAO[] $cB20ITEMAVALIACAOs
 * @property \common\models\CB22COMENTARIOAVALIACAO[] $cB22COMENTARIOAVALIACAOs
 */
class CB19AVALIACAO extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB19_EMPRESA_ID', 'CB19_NOME',], 'required'],
            [['CB19_EMPRESA_ID', 'CB19_STATUS'], 'integer'],
            [['CB19_NOME'], 'string', 'max' => 150]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB19_AVALIACAO';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB19_ID' => 'ID',
            'CB19_EMPRESA_ID' => 'Empresa',
            'CB19_NOME' => 'Nome da avaliação',
            'CB19_DATA' => 'Data',
            'CB19_STATUS' => 'Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB19EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB19_EMPRESA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB20ITEMAVALIACAOs()
    {
        return $this->hasMany(\common\models\CB20ITEMAVALIACAO::className(), ['CB20_AVALIACAO_ID' => 'CB19_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB22COMENTARIOAVALIACAOs()
    {
        return $this->hasMany(\common\models\CB22COMENTARIOAVALIACAO::className(), ['CB22_AVALIACAO_ID' => 'CB19_ID']);
    }
    
    /**
     * @inheritdoc
     * @return \common\models\CB19AVALIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\CB19AVALIACAOQuery(get_called_class());
    }
}
