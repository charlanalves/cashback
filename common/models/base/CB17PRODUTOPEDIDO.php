<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB17_PRODUTO_PEDIDO".
 *
 * @property integer $CB17_ID
 * @property integer $CB17_PRODUTO_ID
 * @property integer $CB17_PEDIDO_ID
 * @property string $CB17_NOME_PRODUTO
 * @property integer $CB17_QTD
 * @property string $CB17_VLR_UNID
 * @property integer $CB17_VARIACAO_ID
 *
 * @property common\models\CB16PEDIDO $cB17PEDIDO
 * @property common\models\CB05PRODUTO $cB17PRODUTO
 * @property common\models\CB06VARIACAO $cB17VARIACAO
 */
class CB17PRODUTOPEDIDO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_NOME_PRODUTO', 'CB17_QTD', 'CB17_VLR_UNID'], 'required'],
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_QTD', 'CB17_VARIACAO_ID'], 'integer'],
            [['CB17_VLR_UNID'], 'number'],
            [['CB17_NOME_PRODUTO'], 'string', 'max' => 100],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB17_PRODUTO_PEDIDO';
    }

   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB17_ID' => 'Cb17  ID',
            'CB17_PRODUTO_ID' => 'Cb17  Produto  ID',
            'CB17_PEDIDO_ID' => 'Cb17  Pedido  ID',
            'CB17_NOME_PRODUTO' => 'Cb17  Nome  Produto',
            'CB17_QTD' => 'Cb17  Qtd',
            'CB17_VLR_UNID' => 'Cb17  Vlr  Unid',
            'CB17_VARIACAO_ID' => 'Cb17  Variacao  ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PEDIDO()
    {
        return $this->hasOne(\common\models\CB16PEDIDO::className(), ['CB16_ID' => 'CB17_PEDIDO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTO()
    {
        return $this->hasOne(\common\models\CB05PRODUTO::className(), ['CB05_ID' => 'CB17_PRODUTO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17VARIACAO()
    {
        return $this->hasOne(\common\models\CB06VARIACAO::className(), ['CB06_ID' => 'CB17_VARIACAO_ID']);
    }
    

}
