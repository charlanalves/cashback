<?php

namespace common\models;

use Yii;
use common\models\base\PAG01TRANSACAO as BasePAG01TRANSACAO;

/**
 * This is the model class for table "PAG01_TRANSACAO".
 */
class PAG01TRANSACAO extends BasePAG01TRANSACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
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
            
            
        ]);
    }
	
}
