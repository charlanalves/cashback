<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "CB23_TIPO_AVALIACAO".
 *
 * @property integer $CB23_ID
 * @property integer $CB23_CATEGORIA_ID
 * @property string $CB23_DESCRICAO
 * @property string $CB23_ICONE
 * @property integer $CB23_STATUS
 *
 * @property \common\models\CB20ITEMAVALIACAO[] $cB20ITEMAVALIACAOs
 * @property \common\models\CB10CATEGORIA $cB23CATEGORIA
 */
class CB23TIPOAVALIACAO extends \common\models\GlobalModel
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB23_CATEGORIA_ID', 'CB23_DESCRICAO', 'CB23_ICONE'], 'required'],
            [['CB23_CATEGORIA_ID', 'CB23_STATUS'], 'integer'],
            [['CB23_DESCRICAO'], 'string', 'max' => 100],
            [['CB23_ICONE'], 'string', 'max' => 50]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB23_TIPO_AVALIACAO';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB23_ID' => 'Cb23  ID',
            'CB23_CATEGORIA_ID' => 'Cb23  Categoria  ID',
            'CB23_DESCRICAO' => 'Cb23  Descricao',
            'CB23_ICONE' => 'Cb23  Icone',
            'CB23_STATUS' => 'Cb23  Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB20ITEMAVALIACAOs()
    {
        return $this->hasMany(\common\models\CB20ITEMAVALIACAO::className(), ['CB20_TIPO_AVALICAO_ID' => 'CB23_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB23CATEGORIA()
    {
        return $this->hasOne(\common\models\CB10CATEGORIA::className(), ['CB10_ID' => 'CB23_CATEGORIA_ID']);
    }
    
    /**
     * @inheritdoc
     * @return \common\models\CB23TIPOAVALIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\CB23TIPOAVALIACAOQuery(get_called_class());
    }
}
