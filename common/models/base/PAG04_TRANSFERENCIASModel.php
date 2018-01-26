<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "PAG04_TRANSFERENCIAS".
 *
 * @property integer $PAG04_ID
 * @property string $PAG04_DATA_CRIACAO
 * @property string $PAG04_DT_PREV
 * @property string $PAG04_DT_DEP
 * @property integer $PAG04_ID_PEDIDO
 * @property integer $PAG04_ID_USER_CONTA_ORIGEM
 * @property integer $PAG04_ID_USER_CONTA_DESTINO
 * @property string $PAG04_VLR
 * @property string $PAG04_TIPO
 *
 * @property \app\models\User $pAG04IDUSERCONTAORIGEM
 * @property \app\models\CB16PEDIDO $pAG04IDPEDIDO
 * @property \app\models\User $pAG04IDUSERCONTADESTINO
 */
class PAG04_TRANSFERENCIASModel extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_DT_PREV', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO', 'PAG04_VLR', 'PAG04_TIPO'], 'required'],
            [['PAG04_ID_PEDIDO', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            [['PAG04_TIPO'], 'string', 'max' => 5],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG04_TRANSFERENCIAS';
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
            'PAG04_ID' => 'Pag04  ID',
            'PAG04_DATA_CRIACAO' => 'Pag04  Data  Criacao',
            'PAG04_DT_PREV' => 'Pag04  Dt  Prev',
            'PAG04_DT_DEP' => 'Pag04  Dt  Dep',
            'PAG04_ID_PEDIDO' => 'Pag04  Id  Pedido',
            'PAG04_ID_USER_CONTA_ORIGEM' => 'Pag04  Id  User  Conta  Origem',
            'PAG04_ID_USER_CONTA_DESTINO' => 'Pag04  Id  User  Conta  Destino',
            'PAG04_VLR' => 'Pag04  Vlr',
            'PAG04_TIPO' => 'Pag04  Tipo',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDUSERCONTAORIGEM()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'PAG04_ID_USER_CONTA_ORIGEM']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDPEDIDO()
    {
        return $this->hasOne(\app\models\CB16PEDIDO::className(), ['CB16_ID' => 'PAG04_ID_PEDIDO']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDUSERCONTADESTINO()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'PAG04_ID_USER_CONTA_DESTINO']);
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
     * @return \app\models\PAG04_TRANSFERENCIASModelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\PAG04_TRANSFERENCIASModelQuery(get_called_class());
    }
}
