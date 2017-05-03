<?php

namespace common\modules\pagamento\models;

use \common\modules\pagamento\models\base\Pag01Transacao as BasePag01Transacao;

/**
 * This is the model class for table "pag01_transacao".
 */
class Pag01Transacao extends BasePag01Transacao
{
    // cenario utilizado ao exibir o form para checkout - valida os parametros iniciais
    const SCENARIO_OPENCHECKOUT = 'OPENCHECKOUT';
    
    /**
     * @inheritdoc
     * @todo Criar regras de campo obrigatório por gateway e forma de pagamento
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['COD_TRANSACAO', 'GATEWAY', 'FORMA_PAG', 'HASH_RECEBEDOR_PRIMARIO', 'HASH_RECEBEDOR_SECUNDARIO', 'VALOR_TOTAL', 'COMPRADOR_NOME', 'COMPRADOR_DATA_NASCIMENTO', 'COMPRADOR_EMAIL', 'COMPRADOR_CPF', 'COMPRADOR_TEL_DDD', 'COMPRADOR_TEL_NUMERO', 'ENDERECO_LOGRADOURO', 'ENDERECO_BAIRRO', 'ENDERECO_CEP', 'ENDERECO_CIDADE', 'ENDERECO_UF', 'ENDERECO_COMPLEMENTO'], 'required'],
            [['VALOR_TOTAL', 'CARTAO_VLR_PARCELA'], 'number'],
            [['TRANSACAO_DT_CADASTRO'], 'safe'],
//            [['TRANSACAO_DT_CADASTRO'], 'safe'],
            [['COMPRADOR_TEL_DDD', 'COMPRADOR_TEL_NUMERO', 'CARTAO_NUM_PARCELA', 'TRANSACAO_STATUS'], 'integer'],
            [['COD_TRANSACAO', 'GATEWAY', 'FORMA_PAG', 'HASH_RECEBEDOR_PRIMARIO', 'HASH_RECEBEDOR_SECUNDARIO', 'COMPRADOR_NOME', 'COMPRADOR_EMAIL', 'ENDERECO_LOGRADOURO', 'ENDERECO_BAIRRO', 'ENDERECO_CIDADE', 'ENDERECO_COMPLEMENTO', 'CARTAO_NOME'], 'string', 'max' => 100],
            [['COMPRADOR_CPF'], 'string', 'max' => 14],
            [['ENDERECO_NUMERO'], 'string', 'max' => 5],
            [['ENDERECO_CEP'], 'string', 'max' => 9],
            [['ENDERECO_UF'], 'string', 'max' => 2],
            [['ENDERECO_PAIS'], 'string', 'max' => 3],
            [['CARTAO_TOKEN'], 'string', 'max' => 32],
            [['TOKEN_GATEWAY'], 'string', 'max' => 250],
//            [['lock'], 'default', 'value' => '0'],
//            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_OPENCHECKOUT] = ['VALOR_TOTAL','CARTAO_VLR_PARCELA'];
        return $scenarios;
    }
	
}
