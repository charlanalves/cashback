<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB04_EMPRESA".
 *
 * @property integer $CB04_ID
 * @property string $CB04_DADOS_API_TOKEN
 * @property string $CB04_NOME
 * @property integer $CB04_CATEGORIA_ID
 * @property string $CB04_FUNCIONAMENTO
 * @property string $CB04_OBSERVACAO
 * @property string $CB04_URL_LOGOMARCA
 * @property integer $CB04_STATUS
 * @property integer $CB04_QTD_FAVORITO
 * @property integer $CB04_QTD_COMPARTILHADO
 * @property string $CB04_END_LOGRADOURO
 * @property string $CB04_END_BAIRRO
 * @property string $CB04_END_CIDADE
 * @property string $CB04_END_UF
 * @property string $CB04_END_NUMERO
 * @property string $CB04_END_COMPLEMENTO
 * @property string $CB04_END_CEP
 *
 * @property common\models\CB01TRANSACAO[] $cB01TRANSACAOs
 * @property common\models\CB10CATEGORIA $cB04CATEGORIA
 * @property common\models\CB05PRODUTO[] $cB05PRODUTOs
 * @property common\models\CB09FORMAPAGTOEMPRESA[] $cB09FORMAPAGTOEMPRESAs
 * @property common\models\CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property common\models\CB13FOTOEMPRESA[] $cB13FOTOEMPRESAs
 * @property common\models\CB15LIKEEMPRESA[] $cB15LIKEEMPRESAs
 * @property common\models\User[] $cB15USERs
 * @property common\models\User[] $users
 */
class CB04EMPRESA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB04_DADOS_API_TOKEN', 'CB04_NOME', 'CB04_CATEGORIA_ID', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO', 'CB04_END_COMPLEMENTO', 'CB04_END_CEP'], 'required'],
            [['CB04_DADOS_API_TOKEN', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO'], 'string'],
            [['CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO'], 'integer'],
            [['CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_URL_LOGOMARCA'], 'string', 'max' => 100],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_CEP'], 'string', 'max' => 8],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB04_EMPRESA';
    }

  
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB04_ID' => 'Cb04  ID',
            'CB04_DADOS_API_TOKEN' => 'Cb04  Dados  Api  Token',
            'CB04_NOME' => 'Cb04  Nome',
            'CB04_CATEGORIA_ID' => 'Cb04  Categoria  ID',
            'CB04_FUNCIONAMENTO' => 'Cb04  Funcionamento',
            'CB04_OBSERVACAO' => 'Cb04  Observacao',
            'CB04_URL_LOGOMARCA' => 'Cb04  Url  Logomarca',
            'CB04_STATUS' => 'Cb04  Status',
            'CB04_QTD_FAVORITO' => 'Cb04  Qtd  Favorito',
            'CB04_QTD_COMPARTILHADO' => 'Cb04  Qtd  Compartilhado',
            'CB04_END_LOGRADOURO' => 'Cb04  End  Logradouro',
            'CB04_END_BAIRRO' => 'Cb04  End  Bairro',
            'CB04_END_CIDADE' => 'Cb04  End  Cidade',
            'CB04_END_UF' => 'Cb04  End  Uf',
            'CB04_END_NUMERO' => 'Cb04  End  Numero',
            'CB04_END_COMPLEMENTO' => 'Cb04  End  Complemento',
            'CB04_END_CEP' => 'Cb04  End  Cep',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01TRANSACAOs()
    {
        return $this->hasMany(\common\models\CB01TRANSACAO::className(), ['CB01_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB04CATEGORIA()
    {
        return $this->hasOne(\common\models\CB10CATEGORIA::className(), ['CB10_ID' => 'CB04_CATEGORIA_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB05PRODUTOs()
    {
        return $this->hasMany(\common\models\CB05PRODUTO::className(), ['CB05_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09FORMAPAGTOEMPRESAs()
    {
        return $this->hasMany(\common\models\CB09FORMAPAGTOEMPRESA::className(), ['CB09_ID_EMPRESA' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(\common\models\CB12ITEMCATEGEMPRESA::className(), ['CB12_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB13FOTOEMPRESAs()
    {
        return $this->hasMany(\common\models\CB13FOTOEMPRESA::className(), ['CB13_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15LIKEEMPRESAs()
    {
        return $this->hasMany(\common\models\CB15LIKEEMPRESA::className(), ['CB15_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15USERs()
    {
        return $this->hasMany(\common\models\User::className(), ['id' => 'CB15_USER_ID'])->viaTable('CB15_LIKE_EMPRESA', ['CB15_EMPRESA_ID' => 'CB04_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['id_company' => 'CB04_ID']);
    }
    

}
