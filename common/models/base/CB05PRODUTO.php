<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB05_PRODUTO".
 *
 * @property integer $CB05_ID
 * @property integer $CB05_EMPRESA_ID
 * @property string $CB05_NOME_CURTO
 * @property string $CB05_TITULO
 * @property string $CB05_DESCRICAO
 * @property string $CB05_IMPORTANTE
 * @property integer $CB05_ATIVO
 *
 * @property common\models\CB04EMPRESA $cB05EMPRESA
 * @property common\models\CB06VARIACAO[] $cB06VARIACAOs
 * @property common\models\CB07CASHBACK[] $cB07CASHBACKs
 * @property common\models\CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property common\models\CB14FOTOPRODUTO[] $cB14FOTOPRODUTOs
 * @property common\models\CB17PRODUTOPEDIDO[] $cB17PRODUTOPEDIDOs
 */
class CB05PRODUTO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB05_EMPRESA_ID', 'CB05_NOME_CURTO', 'CB05_TITULO'], 'required'],
            [['CB05_EMPRESA_ID', 'CB05_ATIVO'], 'integer'],
            [['CB05_DESCRICAO', 'CB05_IMPORTANTE'], 'string'],
            [['CB05_NOME_CURTO'], 'string', 'max' => 15],
            [['CB05_TITULO'], 'string', 'max' => 30],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB05_PRODUTO';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB05_ID' => 'Cb05  ID',
            'CB05_EMPRESA_ID' => 'Empresa  ID',
            'CB05_NOME_CURTO' => 'Nome  Curto',
            'CB05_TITULO' => 'Título',
            'CB05_DESCRICAO' => 'Descrição',
            'CB05_IMPORTANTE' => 'Importante',
            'CB05_ATIVO' => 'Ativo',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB05EMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB05_EMPRESA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB06VARIACAOs()
    {
        return $this->hasMany(\common\models\CB06VARIACAO::className(), ['CB06_PRODUTO_ID' => 'CB05_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs()
    {
        return $this->hasMany(\common\models\CB07CASHBACK::className(), ['CB07_PRODUTO_ID' => 'CB05_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(\common\models\CB12ITEMCATEGEMPRESA::className(), ['CB12_PRODUTO_ID' => 'CB05_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB14FOTOPRODUTOs()
    {
        return $this->hasMany(\common\models\CB14FOTOPRODUTO::className(), ['CB14_PRODUTO_ID' => 'CB05_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTOPEDIDOs()
    {
        return $this->hasMany(\common\models\CB17PRODUTOPEDIDO::className(), ['CB17_PRODUTO_ID' => 'CB05_ID']);
    }
    

}
