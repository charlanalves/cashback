<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "PAG04_TRANSFERENCIAS".
 *
 * @property integer $PAG04_ID
 * @property string $PAG04_DATA_CRIACAO
 * @property integer $PAG04_ID_TRANSACAO
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
            [['PAG04_DATA_CRIACAO', 'PAG04_ID_TRANSACAO', 'PAG04_VLR', 'PAG04_DT_PREV', 'PAG04_DT_DEP', 'PAG04_TIPO'], 'required'],
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_ID_TRANSACAO', 'PAG04_ID_USER', 'PAG04_TIPO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            [['PAG04_COD_CONTA_ORIGEM', 'PAG04_COD_CONTA_DESTINO'], 'string', 'max' => 200],
            
            
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG04_ID' => 'Pag04  ID',
            'PAG04_DATA_CRIACAO' => 'Pag04  Data  Criacao',
            'PAG04_ID_TRANSACAO' => 'Pag04  Id  Transacao',
            'PAG04_ID_USER' => 'Pag04  Id  User',
            'PAG04_COD_CONTA_ORIGEM' => 'Pag04  Cod  Conta  Origem',
            'PAG04_COD_CONTA_DESTINO' => 'Pag04  Cod  Conta  Destino',
            'PAG04_VLR' => 'Pag04  Vlr',
            'PAG04_DT_PREV' => 'Pag04  Dt  Prev',
            'PAG04_DT_DEP' => 'Pag04  Dt  Dep',
            'PAG04_TIPO' => 'Pag04  Tipo',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDTRANSACAO()
    {
        return $this->hasOne(\common\models\PAG01TRANSACAO::className(), ['PAG01_ID' => 'PAG04_ID_TRANSACAO']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04IDUSER()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'PAG04_ID_USER']);
    }
    

}
