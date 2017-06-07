<?php

namespace common\models\base;

use Yii;


/**
 * This is the base model class for table "CB18_VARIACAO_PEDIDO".
 *
 * @property integer $CB18_ID
 * @property integer $CB18_ID_VARIACAO
 * @property integer $CB18_ID_PEDIDO
 * @property string $CB18_VLR
 * @property string $CB18_DATA_CRIACAO
 *
 * @property \app\models\CB17PRODUTOPEDIDO $cB18IDPEDIDO
 * @property \app\models\CB06VARIACAO $cB18IDVARIACAO
 */
class CB18VARIACAOPEDIDO  extends \common\models\GlobalModel
{
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB18_ID_VARIACAO', 'CB18_ID_PEDIDO', 'CB18_VLR'], 'required'],
            [['CB18_ID_VARIACAO', 'CB18_ID_PEDIDO'], 'integer'],
            [['CB18_VLR'], 'number'],
            [['CB18_DATA_CRIACAO'], 'safe'],
          
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB18_VARIACAO_PEDIDO';
    }

  
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB18_ID' => 'Cb18  ID',
            'CB18_ID_VARIACAO' => 'Cb18  Id  Variacao',
            'CB18_ID_PEDIDO' => 'Cb18  Id  Pedido',
            'CB18_VLR' => 'Cb18  Vlr',
            'CB18_DATA_CRIACAO' => 'Cb18  Data  Criacao',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB18IDPEDIDO()
    {
        return $this->hasOne(common\models\CB17PRODUTOPEDIDO::className(), ['CB17_ID' => 'CB18_ID_PEDIDO']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB18IDVARIACAO()
    {
        return $this->hasOne(common\models\CB06VARIACAO::className(), ['CB06_ID' => 'CB18_ID_VARIACAO']);
    }
    

}
