<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB06_VARIACAO".
 *
 * @property integer $CB06_ID
 * @property integer $CB06_PRODUTO_ID
 * @property string $CB06_TITULO
 * @property string $CB06_DESCRICAO
 * @property string $CB06_PRECO
 * @property string $CB06_PRECO_PROMOCIONAL
 * @property string $CB06_DINHEIRO_VOLTA
 * @property string $CB06_TEMPO_MIN
 * @property string $CB06_TEMPO_MAX
 *
 * @property common\models\CB05PRODUTO $cB06PRODUTO
 * @property common\models\CB07CASHBACK[] $cB07CASHBACKs
 * @property common\models\CB17PRODUTOPEDIDO[] $cB17PRODUTOPEDIDOs
 */
class CB06VARIACAO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB06_PRODUTO_ID', 'CB06_DESCRICAO', 'CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'required'],
            [['CB06_PRODUTO_ID', 'CB06_TEMPO_MIN', 'CB06_TEMPO_MAX'], 'integer'],
            [['CB06_DISTRIBUICAO'], 'integer', 'min' => 0, 'max' => 1],
            [['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB06_VARIACAO';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => 'Cb06  ID',
            'CB06_PRODUTO_ID' => 'Cb06  Produto  ID',
            'CB06_TITULO' => 'Cb06  Titulo',
            'CB06_DESCRICAO' => 'Cb06  Descricao',
            'CB06_PRECO' => 'Cb06  Preco',
            'CB06_PRECO_PROMOCIONAL' => 'Cb06  Preco  Promocional',
            'CB06_DINHEIRO_VOLTA' => 'Dinheiro de volta',
            'CB06_TEMPO_MIN' => 'Tempo mínimo',
            'CB06_TEMPO_MAX' => 'Tempo máximo',
            'CB06_DISTRIBUICAO' => 'Distribuição',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB06PRODUTO()
    {
        return $this->hasOne(\common\models\CB05PRODUTO::className(), ['CB05_ID' => 'CB06_PRODUTO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs()
    {
        return $this->hasMany(\common\models\CB07CASHBACK::className(), ['CB07_VARIACAO_ID' => 'CB06_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTOPEDIDOs()
    {
        return $this->hasMany(\common\models\CB17PRODUTOPEDIDO::className(), ['CB17_VARIACAO_ID' => 'CB06_ID']);
    }
    

}
