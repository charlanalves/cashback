<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "CB21_RESPOSTA_AVALIACAO".
 *
 * @property integer $CB21_ID
 * @property integer $CB21_ITEM_AVALIACAO_ID
 * @property integer $CB21_PRODUTO_PEDIDO_ID
 * @property integer $CB21_NOTA
 *
 * @property \common\models\CB20ITEMAVALIACAO $cB21ITEMAVALIACAO
 * @property \common\models\CB17PRODUTOPEDIDO $cB21PRODUTOPEDIDO
 */
class CB21RESPOSTAAVALIACAO extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB21_ITEM_AVALIACAO_ID', 'CB21_PRODUTO_PEDIDO_ID', 'CB21_NOTA'], 'required'],
            [['CB21_ITEM_AVALIACAO_ID', 'CB21_PRODUTO_PEDIDO_ID', 'CB21_NOTA'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB21_RESPOSTA_AVALIACAO';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB21_ID' => 'Cb21  ID',
            'CB21_ITEM_AVALIACAO_ID' => 'Cb21  Item  Avaliacao  ID',
            'CB21_PRODUTO_PEDIDO_ID' => 'Cb21  Produto  Pedido  ID',
            'CB21_NOTA' => 'Cb21  Nota',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB21ITEMAVALIACAO()
    {
        return $this->hasOne(\common\models\CB20ITEMAVALIACAO::className(), ['CB20_ID' => 'CB21_ITEM_AVALIACAO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB21PRODUTOPEDIDO()
    {
        return $this->hasOne(\common\models\CB17PRODUTOPEDIDO::className(), ['CB17_ID' => 'CB21_PRODUTO_PEDIDO_ID']);
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
     * @return \common\models\CB21RESPOSTAAVALIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\CB21RESPOSTAAVALIACAOQuery(get_called_class());
    }
}
