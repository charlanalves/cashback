<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "PAG04_TRANSFERENCIAS".
 *
 * @property integer $PAG04_ID
 * @property string $PAG04_DATA_CRIACAO 
 * @property integer $PAG04_ID_USER
 * @property string $PAG04_COD_CONTA_ORIGEM
 * @property string $PAG04_COD_CONTA_DESTINO
 * @property string $PAG04_VLR
 * @property string $PAG04_DT_PREV
 * @property string $PAG04_DT_DEP
 * @property integer $PAG04_TIPO
 *
 * @property common\models\PAG01TRANSACAO $pAG04IDTRANSACAO
 * @property common\models\User $pAG04IDUSER
 */
class PAG04TRANSFERENCIAS extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG04_STATUS','PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP','PAG04_VLR'], 'safe'],
            [['PAG04_DT_PREV', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_VLR', 'PAG04_TIPO'], 'required'],
            [['PAG04_ID_PEDIDO', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO'], 'integer'],            
            [['PAG04_TIPO'], 'string', 'max' => 5],
            
        ];
    }
   


     /*
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG04_TRANSFERENCIAS';
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
   public function getPAG04IDPEDIDO() 
    { 
        return $this->hasOne(common\models\CB16PEDIDO::className(), ['CB16_ID' => 'PAG04_ID_PEDIDO']);
    } 
    
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDUSERCONTAORIGEM()
    {
        return $this->hasOne(common\models\User::className(), ['id' => 'PAG04_ID_USER_CONTA_ORIGEM']);
    }
  
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDUSERCONTADESTINO()
    {
        return $this->hasOne(common\models\User::className(), ['id' => 'PAG04_ID_USER_CONTA_DESTINO']);
    }

}
