<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "CB22_COMENTARIO_AVALIACAO".
 *
 * @property integer $CB22_ID
 * @property integer $CB22_AVALIACAO_ID
 * @property integer $CB22_PRODUTO_PEDIDO_ID
 * @property string $CB22_COMENTARIO
 *
 * @property \common\models\CB19AVALIACAO $cB22AVALIACAO
 * @property \common\models\CB17PRODUTOPEDIDO $cB22PRODUTOPEDIDO
 */
class CB22COMENTARIOAVALIACAO extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB22_AVALIACAO_ID', 'CB22_PRODUTO_PEDIDO_ID', 'CB22_COMENTARIO'], 'required'],
            [['CB22_AVALIACAO_ID', 'CB22_PRODUTO_PEDIDO_ID'], 'integer'],
            [['CB22_COMENTARIO'], 'string', 'max' => 250]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB22_COMENTARIO_AVALIACAO';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB22_ID' => 'Cb22  ID',
            'CB22_AVALIACAO_ID' => 'Cb22  Avaliacao  ID',
            'CB22_PRODUTO_PEDIDO_ID' => 'Cb22  Produto  Pedido  ID',
            'CB22_COMENTARIO' => 'Cb22  Comentario',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB22AVALIACAO()
    {
        return $this->hasOne(\common\models\CB19AVALIACAO::className(), ['CB19_ID' => 'CB22_AVALIACAO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB22PRODUTOPEDIDO()
    {
        return $this->hasOne(\common\models\CB17PRODUTOPEDIDO::className(), ['CB17_ID' => 'CB22_PRODUTO_PEDIDO_ID']);
    }
    
/**
     * @inheritdoc
     * @return array mixed
     */ 
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\CB22COMENTARIOAVALIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\CB22COMENTARIOAVALIACAOQuery(get_called_class());
    }
}
