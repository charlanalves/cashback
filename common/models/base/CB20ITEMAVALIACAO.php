<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "CB20_ITEM_AVALIACAO".
 *
 * @property integer $CB20_ID
 * @property integer $CB20_AVALIACAO_ID
 * @property integer $CB20_TIPO_AVALICAO_ID
 * @property integer $CB20_STATUS
 *
 * @property \common\models\CB19AVALIACAO $cB20AVALIACAO
 * @property \common\models\CB23TIPOAVALIACAO $cB20TIPOAVALICAO
 * @property \common\models\CB21RESPOSTAAVALIACAO[] $cB21RESPOSTAAVALIACAOs
 */
class CB20ITEMAVALIACAO extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB20_AVALIACAO_ID', 'CB20_TIPO_AVALICAO_ID'], 'required'],
            [['CB20_AVALIACAO_ID', 'CB20_TIPO_AVALICAO_ID', 'CB20_STATUS'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB20_ITEM_AVALIACAO';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB20_ID' => 'ID',
            'CB20_AVALIACAO_ID' => 'Avaliacao',
            'CB20_TIPO_AVALICAO_ID' => 'Tipo Avalicao',
            'CB20_STATUS' => 'Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB20AVALIACAO()
    {
        return $this->hasOne(\common\models\CB19AVALIACAO::className(), ['CB19_ID' => 'CB20_AVALIACAO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB20TIPOAVALICAO()
    {
        return $this->hasOne(\common\models\CB23TIPOAVALIACAO::className(), ['CB23_ID' => 'CB20_TIPO_AVALICAO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB21RESPOSTAAVALIACAOs()
    {
        return $this->hasMany(\common\models\CB21RESPOSTAAVALIACAO::className(), ['CB21_ITEM_AVALIACAO_ID' => 'CB20_ID']);
    }
    
    /**
     * @inheritdoc
     * @return \common\models\CB20ITEMAVALIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\CB20ITEMAVALIACAOQuery(get_called_class());
    }
}
