<?php

namespace app\common\models;

use Yii;
use \app\common\models\base\PAG01_TRANSACAO as BasePAG01_TRANSACAO;

/**
 * This is the model class for table "PAG01_TRANSACAO".
 */
class PAG01_TRANSACAO extends BasePAG01_TRANSACAO
{	
    
        
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG01_COD_TRANSACAO', 'PAG01_GATEWAY', 'PAG01_FORMA_PAG', 'PAG01_HASH_RECEBEDOR_PRIMARIO', 'PAG01_HASH_RECEBEDOR_SECUNDARIO', 'PAG01_VALOR_TOTAL', 'PAG01_COMPRADOR_NOME', 'PAG01_COMPRADOR_DATA_NASCIMENTO', 'PAG01_COMPRADOR_EMAIL', 'PAG01_COMPRADOR_CPF', 'PAG01_COMPRADOR_TEL_DDD', 'PAG01_COMPRADOR_TEL_NUMERO', 'PAG01_ENDERECO_LOGRADOURO', 'PAG01_ENDERECO_BAIRRO', 'PAG01_ENDERECO_CEP', 'PAG01_ENDERECO_CIDADE', 'PAG01_ENDERECO_UF', 'PAG01_ENDERECO_COMPLEMENTO'], 'required'],
            [['PAG01_VALOR_TOTAL', 'PAG01_CARTAO_VLR_PARCELA'], 'number'],
            [['PAG01_COMPRADOR_DATA_NASCIMENTO', 'PAG01_TRANSACAO_DT_CADASTRO'], 'safe'],
            [['PAG01_COMPRADOR_TEL_DDD', 'PAG01_COMPRADOR_TEL_NUMERO', 'PAG01_CARTAO_NUM_PARCELA', 'PAG01_TRANSACAO_STATUS'], 'integer'],
            [['PAG01_COD_TRANSACAO', 'PAG01_GATEWAY', 'PAG01_FORMA_PAG', 'PAG01_HASH_RECEBEDOR_PRIMARIO', 'PAG01_HASH_RECEBEDOR_SECUNDARIO', 'PAG01_COMPRADOR_NOME', 'PAG01_COMPRADOR_EMAIL', 'PAG01_ENDERECO_LOGRADOURO', 'PAG01_ENDERECO_BAIRRO', 'PAG01_ENDERECO_CIDADE', 'PAG01_ENDERECO_COMPLEMENTO', 'PAG01_CARTAO_NOME'], 'string', 'max' => 100],
            [['PAG01_COMPRADOR_CPF'], 'string', 'max' => 14],
            [['PAG01_ENDERECO_NUMERO'], 'string', 'max' => 5],
            [['PAG01_ENDERECO_CEP'], 'string', 'max' => 8],
            [['PAG01_ENDERECO_UF'], 'string', 'max' => 2],
            [['PAG01_ENDERECO_PAIS'], 'string', 'max' => 3],
            [['PAG01_CARTAO_TOKEN'], 'string', 'max' => 32],
            [['PAG01_TOKEN_GATEWAY'], 'string', 'max' => 200]
        ]);
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG01_ID' => Yii::t('app','Pag01  ID'),
            'PAG01_COD_TRANSACAO' => Yii::t('app','Pag01  Cod  Transacao'),
            'PAG01_GATEWAY' => Yii::t('app','Pag01  Gateway'),
            'PAG01_FORMA_PAG' => Yii::t('app','Pag01  Forma  Pag'),
            'PAG01_HASH_RECEBEDOR_PRIMARIO' => Yii::t('app','Pag01  Hash  Recebedor  Primario'),
            'PAG01_HASH_RECEBEDOR_SECUNDARIO' => Yii::t('app','Pag01  Hash  Recebedor  Secundario'),
            'PAG01_VALOR_TOTAL' => Yii::t('app','Pag01  Valor  Total'),
            'PAG01_COMPRADOR_NOME' => Yii::t('app','Pag01  Comprador  Nome'),
            'PAG01_COMPRADOR_DATA_NASCIMENTO' => Yii::t('app','Pag01  Comprador  Data  Nascimento'),
            'PAG01_COMPRADOR_EMAIL' => Yii::t('app','Pag01  Comprador  Email'),
            'PAG01_COMPRADOR_CPF' => Yii::t('app','Pag01  Comprador  Cpf'),
            'PAG01_COMPRADOR_TEL_DDD' => Yii::t('app','Pag01  Comprador  Tel  Ddd'),
            'PAG01_COMPRADOR_TEL_NUMERO' => Yii::t('app','Pag01  Comprador  Tel  Numero'),
            'PAG01_ENDERECO_LOGRADOURO' => Yii::t('app','Pag01  Endereco  Logradouro'),
            'PAG01_ENDERECO_NUMERO' => Yii::t('app','Pag01  Endereco  Numero'),
            'PAG01_ENDERECO_BAIRRO' => Yii::t('app','Pag01  Endereco  Bairro'),
            'PAG01_ENDERECO_CEP' => Yii::t('app','Pag01  Endereco  Cep'),
            'PAG01_ENDERECO_CIDADE' => Yii::t('app','Pag01  Endereco  Cidade'),
            'PAG01_ENDERECO_UF' => Yii::t('app','Pag01  Endereco  Uf'),
            'PAG01_ENDERECO_PAIS' => Yii::t('app','Pag01  Endereco  Pais'),
            'PAG01_ENDERECO_COMPLEMENTO' => Yii::t('app','Pag01  Endereco  Complemento'),
            'PAG01_CARTAO_TOKEN' => Yii::t('app','Pag01  Cartao  Token'),
            'PAG01_CARTAO_NOME' => Yii::t('app','Pag01  Cartao  Nome'),
            'PAG01_CARTAO_NUM_PARCELA' => Yii::t('app','Pag01  Cartao  Num  Parcela'),
            'PAG01_CARTAO_VLR_PARCELA' => Yii::t('app','Pag01  Cartao  Vlr  Parcela'),
            'PAG01_TRANSACAO_STATUS' => Yii::t('app','Pag01  Transacao  Status'),
            'PAG01_TRANSACAO_DT_CADASTRO' => Yii::t('app','Pag01  Transacao  Dt  Cadastro'),
            'PAG01_TOKEN_GATEWAY' => Yii::t('app','Pag01  Token  Gateway'),
        ];
    }
    
    



    /**
    * @inheritdoc
    */
    public function gridQueryMain()
    {
	    $query =  "
            	    			    		 SELECT PAG01_TRANSACAO.PAG01_ID AS ID, PAG01_TRANSACAO.PAG01_COD_TRANSACAO,PAG01_TRANSACAO.PAG01_GATEWAY
	 	    		 FROM PAG01_TRANSACAO
	 	    		 
	 	    		 
	 	        ";
		
		$connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
		$reader = $command->query();
		
		return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsMain()
    {
    	$al = $this->attributeLabels();
        return [
            ['btnsAvailable' => ['editar', 'excluir']],
            ['sets' => ['title'=>Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],        
		    ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
                        ['sets' => ['title' => $al['PAG01_COD_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG01_COD_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
                                    ['sets' => ['title' => $al['PAG01_GATEWAY'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG01_GATEWAY' ], 'filter' => ['title'=>'#text_filter']], 
                        				
       		 ];
    }


}
