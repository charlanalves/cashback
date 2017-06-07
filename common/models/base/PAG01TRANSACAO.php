<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "PAG01_TRANSACAO".
 *
 * @property integer $PAG01_ID
 * @property string $PAG01_COD_TRANSACAO
 * @property string $PAG01_NM_ADQ
 * @property string $PAG01_VLR_PEDIDO
 * @property string $PAG01_VLR_PRODUTO
 * @property string $PAG01_FORMA_PAG
 * @property string $PAG01_PERC_CLIENTE
 * @property string $PAG01_PERC_ADMIN
 * @property string $PAG01_PERC_ADQ
 * @property string $PAG01_PERC_ANTIFRAUDE
 * @property string $PAG01_COMPRADOR_NOME
 * @property string $PAG01_COMPRADOR_DATA_NASCIMENTO
 * @property string $PAG01_COMPRADOR_EMAIL
 * @property string $PAG01_COMPRADOR_CPF
 * @property integer $PAG01_COMPRADOR_TEL_DDD
 * @property integer $PAG01_COMPRADOR_TEL_NUMERO
 * @property string $PAG01_ENDERECO_LOGRADOURO
 * @property string $PAG01_ENDERECO_NUMERO
 * @property string $PAG01_ENDERECO_BAIRRO
 * @property string $PAG01_ENDERECO_CEP
 * @property string $PAG01_ENDERECO_CIDADE
 * @property string $PAG01_ENDERECO_UF
 * @property string $PAG01_ENDERECO_PAIS
 * @property string $PAG01_ENDERECO_COMPLEMENTO
 * @property string $PAG01_CARTAO_TOKEN
 * @property string $PAG01_CARTAO_NOME
 * @property integer $PAG01_CARTAO_NUM_PARCELA
 * @property string $PAG01_CARTAO_VLR_PARCELA
 * @property integer $PAG01_TRANSACAO_STATUS
 * @property string $PAG01_TRANSACAO_DT_CADASTRO
 * @property string $PAG01_TOKEN_GATEWAY
 *
 * @property common\models\PAG04TRANSFERENCIAS[] $pAG04TRANSFERENCIASs
 */
class PAG01TRANSACAO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG01_COD_TRANSACAO', 'PAG01_NM_ADQ', 'PAG01_VLR_PEDIDO', 'PAG01_VLR_PRODUTO', 'PAG01_FORMA_PAG', 'PAG01_PERC_CLIENTE', 'PAG01_PERC_ADMIN', 'PAG01_PERC_ADQ', 'PAG01_COMPRADOR_NOME', 'PAG01_COMPRADOR_DATA_NASCIMENTO', 'PAG01_COMPRADOR_EMAIL', 'PAG01_COMPRADOR_CPF', 'PAG01_COMPRADOR_TEL_DDD', 'PAG01_COMPRADOR_TEL_NUMERO', 'PAG01_ENDERECO_LOGRADOURO', 'PAG01_ENDERECO_BAIRRO', 'PAG01_ENDERECO_CEP', 'PAG01_ENDERECO_CIDADE', 'PAG01_ENDERECO_UF', 'PAG01_ENDERECO_COMPLEMENTO'], 'required'],
            [['PAG01_VLR_PEDIDO', 'PAG01_VLR_PRODUTO', 'PAG01_PERC_CLIENTE', 'PAG01_PERC_ADMIN', 'PAG01_PERC_ADQ', 'PAG01_PERC_ANTIFRAUDE', 'PAG01_CARTAO_VLR_PARCELA'], 'number'],
            [['PAG01_COMPRADOR_DATA_NASCIMENTO', 'PAG01_TRANSACAO_DT_CADASTRO'], 'safe'],
            [['PAG01_COMPRADOR_TEL_DDD', 'PAG01_COMPRADOR_TEL_NUMERO', 'PAG01_CARTAO_NUM_PARCELA', 'PAG01_TRANSACAO_STATUS'], 'integer'],
            [['PAG01_COD_TRANSACAO', 'PAG01_NM_ADQ', 'PAG01_FORMA_PAG', 'PAG01_COMPRADOR_NOME', 'PAG01_COMPRADOR_EMAIL', 'PAG01_ENDERECO_LOGRADOURO', 'PAG01_ENDERECO_BAIRRO', 'PAG01_ENDERECO_CIDADE', 'PAG01_ENDERECO_COMPLEMENTO', 'PAG01_CARTAO_NOME'], 'string', 'max' => 100],
            [['PAG01_COMPRADOR_CPF'], 'string', 'max' => 14],
            [['PAG01_ENDERECO_NUMERO'], 'string', 'max' => 5],
            [['PAG01_ENDERECO_CEP'], 'string', 'max' => 8],
            [['PAG01_ENDERECO_UF'], 'string', 'max' => 2],
            [['PAG01_ENDERECO_PAIS'], 'string', 'max' => 3],
            [['PAG01_CARTAO_TOKEN'], 'string', 'max' => 32],
            [['PAG01_TOKEN_GATEWAY'], 'string', 'max' => 200],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG01_TRANSACAO';
    }

   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG01_ID' => 'Pag01  ID',
            'PAG01_COD_TRANSACAO' => 'Pag01  Cod  Transacao',
            'PAG01_NM_ADQ' => 'Pag01  Nm  Adq',
            'PAG01_VLR_PEDIDO' => 'Pag01  Vlr  Pedido',
            'PAG01_VLR_PRODUTO' => 'Pag01  Vlr  Produto',
            'PAG01_FORMA_PAG' => 'Pag01  Forma  Pag',
            'PAG01_PERC_CLIENTE' => 'Pag01  Perc  Cliente',
            'PAG01_PERC_ADMIN' => 'Pag01  Perc  Admin',
            'PAG01_PERC_ADQ' => 'Pag01  Perc  Adq',
            'PAG01_PERC_ANTIFRAUDE' => 'Pag01  Perc  Antifraude',
            'PAG01_COMPRADOR_NOME' => 'Pag01  Comprador  Nome',
            'PAG01_COMPRADOR_DATA_NASCIMENTO' => 'Pag01  Comprador  Data  Nascimento',
            'PAG01_COMPRADOR_EMAIL' => 'Pag01  Comprador  Email',
            'PAG01_COMPRADOR_CPF' => 'Pag01  Comprador  Cpf',
            'PAG01_COMPRADOR_TEL_DDD' => 'Pag01  Comprador  Tel  Ddd',
            'PAG01_COMPRADOR_TEL_NUMERO' => 'Pag01  Comprador  Tel  Numero',
            'PAG01_ENDERECO_LOGRADOURO' => 'Pag01  Endereco  Logradouro',
            'PAG01_ENDERECO_NUMERO' => 'Pag01  Endereco  Numero',
            'PAG01_ENDERECO_BAIRRO' => 'Pag01  Endereco  Bairro',
            'PAG01_ENDERECO_CEP' => 'Pag01  Endereco  Cep',
            'PAG01_ENDERECO_CIDADE' => 'Pag01  Endereco  Cidade',
            'PAG01_ENDERECO_UF' => 'Pag01  Endereco  Uf',
            'PAG01_ENDERECO_PAIS' => 'Pag01  Endereco  Pais',
            'PAG01_ENDERECO_COMPLEMENTO' => 'Pag01  Endereco  Complemento',
            'PAG01_CARTAO_TOKEN' => 'Pag01  Cartao  Token',
            'PAG01_CARTAO_NOME' => 'Pag01  Cartao  Nome',
            'PAG01_CARTAO_NUM_PARCELA' => 'Pag01  Cartao  Num  Parcela',
            'PAG01_CARTAO_VLR_PARCELA' => 'Pag01  Cartao  Vlr  Parcela',
            'PAG01_TRANSACAO_STATUS' => 'Pag01  Transacao  Status',
            'PAG01_TRANSACAO_DT_CADASTRO' => 'Pag01  Transacao  Dt  Cadastro',
            'PAG01_TOKEN_GATEWAY' => 'Pag01  Token  Gateway',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04TRANSFERENCIASs()
    {
        return $this->hasMany(\common\models\PAG04TRANSFERENCIAS::className(), ['PAG04_ID_TRANSACAO' => 'PAG01_ID']);
    }
    

}
