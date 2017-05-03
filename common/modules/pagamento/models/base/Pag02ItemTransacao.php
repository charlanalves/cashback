<?php

namespace common\modules\pagamento\models\base;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;
use \common\models\GlobalModel;

/**
 * This is the base model class for table "pag02_item_transacao".
 *
 * @property integer $ID_ITEM_TRANSACAO
 * @property string $ITEM_COD
 * @property string $ITEM_DESC
 * @property integer $ITEM_QTD
 * @property string $ITEM_VLR
 * @property integer $ITEM_STATUS
 * @property string $ITEM_DT_INCLUSAO
 * @property integer $ID_TRANSACAO
 */
class Pag02ItemTransacao extends GlobalModel
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ITEM_COD', 'ITEM_DESC', 'ITEM_VLR', 'ID_TRANSACAO'], 'required'],
            [['ITEM_QTD', 'ITEM_STATUS', 'ID_TRANSACAO'], 'integer'],
            [['ITEM_VLR'], 'number'],
//            [['ITEM_DT_INCLUSAO'], 'safe'],
            [['ITEM_COD', 'ITEM_DESC'], 'string', 'max' => 100],
//            [['lock'], 'default', 'value' => '0'],
//            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG02_ITEM_TRANSACAO';
    }

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_ITEM_TRANSACAO' => 'Id  Item  Transacao',
            'ITEM_COD' => 'Item  Cod',
            'ITEM_DESC' => 'Item  Desc',
            'ITEM_QTD' => 'Item  Qtd',
            'ITEM_VLR' => 'Item  Vlr',
            'ITEM_STATUS' => 'Item  Status',
            'ITEM_DT_INCLUSAO' => 'Item  Dt  Inclusao',
            'ID_TRANSACAO' => 'Id  Transacao',
        ];
    }

/**
     * @inheritdoc
     * @return array mixed
     */ 
//    public function behaviors()
//    {
//        return [
//            'timestamp' => [
//                'class' => TimestampBehavior::className(),
//                'createdAtAttribute' => 'ITEM_DT_INCLUSAO',
//                'updatedAtAttribute' => 'ITEM_DT_INCLUSAO',
//                'value' => new \yii\db\Expression('NOW()'),
//            ],
//            'blameable' => [
//                'class' => BlameableBehavior::className(),
//                'createdByAttribute' => 'created_by',
//                'updatedByAttribute' => 'updated_by',
//            ],
//            'uuid' => [
//                'class' => UUIDBehavior::className(),
//                'column' => 'id',
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     * @return \app\models\Pag02ItemTransacaoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Pag02ItemTransacaoQuery(get_called_class());
    }
}
