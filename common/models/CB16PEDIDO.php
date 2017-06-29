<?php

namespace common\models;

use Yii;
use common\models\base\CB16PEDIDO as BaseCB16PEDIDO;

/**
 * This is the model class for table "CB16_PEDIDO".
 */
class CB16PEDIDO extends BaseCB16PEDIDO
{
	
	 // status do pagamento
    public $status_pedido = [1 => 'CANCELADO', 10 => 'AGUARDANDO PAGAMENTO', 20 => 'BAIXADO', 30 => 'PAGO'];
    
    const status_cancelado = 1;
    const status_aguardando_pagamento = 10;
    const status_baixado = 20;
    const status_pago = 30;
    const status_pago_trans_agendadas = 40;
    const status_pago_trans_liberadas = 50;
    const status_pago_trans_realizadas = 60;
   
    const trans_nao_criadas = 0;
    const trans_criadas = 1;
    
    const SCENARIO_ATUALIZA_PEDIDO_PAGO = 'SCENARIO_ATUALIZA_PEDIDO_PAGO';
    
   
	
 /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
     		'CB16_ID' => 'Cb16  ID',
            'CB16_TRANS_CRIADAS' => 'Cb16  Trans  Criadas',
            'CB16_EMPRESA_ID' => 'Cb16  Empresa  ID',
            'CB16_ID_COMPRADOR' => 'Cb16  Id  Comprador',
            'CB16_USER_ID' => 'Id User',
            'CB16_ID_FORMA_PAG_EMPRESA' => 'Cb16  Id  Forma  Pag  Empresa',
            'CB16_VALOR' => 'Cb16  Vlr',
            'CB16_PERC_ADMIN' => 'Cb16  Perc  Admin',
            'CB16_PERC_ADQ' => 'Cb16  Perc  Adq',
            'CB16_VLR_CB_TOTAL' => 'Cb16  Vlr  Cb  Total',
            'CB16_FRETE' => 'Cb16  Frete',
            'CB16_STATUS' => 'Cb16  Status',
            'CB16_DT' => 'Cb16  Dt',
            'CB16_DT_APROVACAO' => 'Cb16  Dt  Aprovacao',
            'CB16_FORMA_PAG' => 'Cb16  Forma  Pag',
            'CB16_CARTAO_TOKEN' => 'Cb16  Cartao  Token',
            'CB16_CARTAO_NUM_PARCELA' => 'Cb16  Cartao  Num  Parcela',
            'CB16_CARTAO_VLR_PARCELA' => 'Cb16  Cartao  Vlr  Parcela',
            'CB16_COMPRADOR_NOME' => 'Cb16  Comprador  Nome',
            'CB16_COMPRADOR_EMAIL' => 'Cb16  Comprador  Email',
            'CB16_COMPRADOR_CPF' => 'Cb16  Comprador  Cpf',
            'CB16_COMPRADOR_TEL_DDD' => 'Cb16  Comprador  Tel  Ddd',
            'CB16_COMPRADOR_TEL_NUMERO' => 'Cb16  Comprador  Tel  Numero',
            'CB16_COMPRADOR_END_LOGRADOURO' => 'Cb16  Comprador  End  Logradouro',
            'CB16_COMPRADOR_END_NUMERO' => 'Cb16  Comprador  End  Numero',
            'CB16_COMPRADOR_END_BAIRRO' => 'Cb16  Comprador  End  Bairro',
            'CB16_COMPRADOR_END_CEP' => 'Cb16  Comprador  End  Cep',
            'CB16_COMPRADOR_END_CIDADE' => 'Cb16  Comprador  End  Cidade',
            'CB16_COMPRADOR_END_UF' => 'Cb16  Comprador  End  Uf',
            'CB16_COMPRADOR_END_PAIS' => 'Cb16  Comprador  End  Pais',
            'CB16_COMPRADOR_END_COMPLEMENTO' => 'Cb16  Comprador  End  Complemento',
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {  
        return array_replace_recursive(parent::rules(),
         [
            [['CB16_TRANS_CRIADAS', 'CB16_EMPRESA_ID', 'CB16_ID_FORMA_PAG_EMPRESA', 'CB16_STATUS', 'CB16_CARTAO_NUM_PARCELA'], 'integer'],
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_VALOR', 'CB16_VLR_CB_TOTAL'], 'required'],
            [['CB16_ID_FORMA_PAG_EMPRESA', 'CB16_FORMA_PAG', 'CB16_PERC_ADMIN', 'CB16_PERC_ADQ', 'CB16_STATUS', 'CB16_DT_APROVACAO'], 'required' ,'on' => self::SCENARIO_ATUALIZA_PEDIDO_PAGO , 'message'=>'O {attribute} é obrigatório'],
            [['CB16_VALOR', 'CB16_PERC_ADMIN', 'CB16_PERC_ADQ', 'CB16_VLR_CB_TOTAL', 'CB16_FRETE', 'CB16_CARTAO_VLR_PARCELA'], 'number'],
            [['CB16_DT', 'CB16_DT_APROVACAO'], 'safe'],
            [['CB16_CARTAO_TOKEN'], 'string'],
            [['CB16_FORMA_PAG'], 'string', 'max' => 50],
            [['CB16_COMPRADOR_NOME', 'CB16_COMPRADOR_EMAIL', 'CB16_COMPRADOR_CPF', 'CB16_COMPRADOR_TEL_DDD', 'CB16_COMPRADOR_TEL_NUMERO', 'CB16_COMPRADOR_END_LOGRADOURO', 'CB16_COMPRADOR_END_NUMERO', 'CB16_COMPRADOR_END_BAIRRO', 'CB16_COMPRADOR_END_CEP', 'CB16_COMPRADOR_END_CIDADE', 'CB16_COMPRADOR_END_UF'], 'string', 'max' => 100],
            [['CB16_COMPRADOR_END_PAIS'], 'string', 'max' => 2],
            [['CB16_COMPRADOR_END_COMPLEMENTO'], 'string', 'max' => 500],     
        ]);
    }
    
     public static function getPedido($pedido, $usuario)
    {
        
        $sql = "SELECT CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, CB04_EMPRESA.*, CB14_FOTO_PRODUTO.CB14_URL 
                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN CB04_EMPRESA ON(CB16_PEDIDO.CB16_EMPRESA_ID = CB04_EMPRESA.CB04_ID)
                LEFT JOIN CB14_FOTO_PRODUTO ON(CB17_PRODUTO_PEDIDO.CB17_PRODUTO_ID =  CB14_FOTO_PRODUTO.CB14_PRODUTO_ID AND CB14_FOTO_PRODUTO.CB14_CAPA = 1)
                WHERE CB16_ID = :pedido AND CB16_USER_ID = :usuario";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':pedido', $pedido);
        $command->bindValue(':usuario', $usuario);
        return $command->query()->readAll()[0];
        
    }
    
    public static function getPedidoByCPF($cpf, $empresa = null)
    {
        
        $sql = "SELECT CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, user.name, DATE_FORMAT(CB16_DT,'%d/%m/%Y') as CB16_DT 
                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN user ON(user.id = CB16_USER_ID)
                WHERE CB16_EMPRESA_ID = :empresa AND cpf_cnpj = :usuario
                ORDER BY CB16_STATUS DESC, CB16_DT DESC";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':usuario', $cpf);
        $command->bindValue(':empresa', $empresa);
        return $command->query()->readAll();
        
    }
    
    
    public static function getPedidoByAuthKey($key, $empresa = "", $pedido = "")
    {
        
        $sql = "SELECT CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, user.*, DATE_FORMAT(CB16_DT,'%d/%m/%Y') as CB16_DT, CB14_URL AS IMG, 
                    CASE CB16_STATUS 
                    WHEN " . self::status_cancelado . " THEN 'Cancelado'
                    WHEN " . self::status_aguardando_pagamento . " THEN 'Aguardando pagamento'
                    WHEN " . self::status_baixado . " THEN 'Utilizado'
                    WHEN " . self::status_pago . " THEN 'Pago'
                    ELSE '' END AS STATUS
                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN user ON(user.id = CB16_USER_ID)
                LEFT JOIN CB14_FOTO_PRODUTO ON(CB14_PRODUTO_ID = CB17_PRODUTO_ID AND CB14_CAPA = '1')
                WHERE auth_key = :usuario " . (!$empresa ? "" : " AND CB16_EMPRESA_ID = :empresa") . (!$pedido ? "" : " AND CB16_ID = :pedido") . "
                ORDER BY CB16_STATUS DESC, CB16_DT DESC";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':usuario', $key);
        if ($empresa) {
            $command->bindValue(':empresa', $empresa);
        }
        if ($pedido) {
            $command->bindValue(':pedido', $pedido);
        }
        return $command->query()->readAll();
        
    }
    
	public static function getPedidoCompleto()
    {
        
        $sql = "
             SELECT *,USER_EMPRESA.id AS ID_USER_EMPRESA, user.id AS ID_USER_CLIENTE FROM CB16_PEDIDO
				JOIN CB17_PRODUTO_PEDIDO ON CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID = CB16_PEDIDO.CB16_ID  
				JOIN CB06_VARIACAO ON CB06_VARIACAO.CB06_ID = CB17_PRODUTO_PEDIDO.CB17_VARIACAO_ID
				JOIN CB05_PRODUTO ON CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID
				JOIN CB04_EMPRESA ON  CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN user ON  CB16_PEDIDO.CB16_USER_ID = user.id
		 	    JOIN user AS USER_EMPRESA ON USER_EMPRESA.id_company = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
				JOIN CB09_FORMA_PAGTO_EMPRESA ON  CB16_PEDIDO.CB16_ID_FORMA_PAG_EMPRESA = CB09_FORMA_PAGTO_EMPRESA.CB09_ID
				JOIN CB08_FORMA_PAGAMENTO ON CB08_FORMA_PAGAMENTO.CB08_ID = CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG
			WHERE CB16_PEDIDO.CB16_TRANS_CRIADAS = :transCriadas
			AND CB16_PEDIDO.CB16_STATUS = :status
			AND CB16_PEDIDO.CB16_DT_APROVACAO IS NOT NULL
			AND CB16_PEDIDO.CB16_COD_TRANSACAO IS NOT NULL
			GROUP BY CB16_PEDIDO.CB16_ID
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':transCriadas', self::trans_nao_criadas);
        $command->bindValue(':status', self::status_pago);
        

        return $command->query()->readAll();
    }
    
    
    public static function getPedidoCompletoByPedido($pedido)
    {
        
        $sql = "
             SELECT *,USER_EMPRESA.id AS ID_USER_EMPRESA, user.id AS ID_USER_CLIENTE FROM CB16_PEDIDO
				JOIN CB17_PRODUTO_PEDIDO ON CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID = CB16_PEDIDO.CB16_ID  
				JOIN CB06_VARIACAO ON CB06_VARIACAO.CB06_ID = CB17_PRODUTO_PEDIDO.CB17_VARIACAO_ID
				JOIN CB05_PRODUTO ON CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID
				JOIN CB04_EMPRESA ON  CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN user ON  CB16_PEDIDO.CB16_USER_ID = user.id
		 	    JOIN user AS USER_EMPRESA ON USER_EMPRESA.id_company = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
				JOIN CB09_FORMA_PAGTO_EMPRESA ON  CB16_PEDIDO.CB16_ID_FORMA_PAG_EMPRESA = CB09_FORMA_PAGTO_EMPRESA.CB09_ID
				JOIN CB08_FORMA_PAGAMENTO ON CB08_FORMA_PAGAMENTO.CB08_ID = CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG
			WHERE CB16_PEDIDO.CB16_ID = :pedido
			AND CB16_PEDIDO.CB16_DT_APROVACAO IS NOT NULL
			AND CB16_PEDIDO.CB16_COD_TRANSACAO IS NULL
			GROUP BY CB16_PEDIDO.CB16_ID
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':pedido', $pedido);
        return $command->query()->readAll();
    }
    
	public static function getPedidoByStatus($status)
    {
        
        $sql = "
            SELECT * 
            FROM CB16_PEDIDO
				JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
			WHERE CB16_PEDIDO.CB16_STATUS = :status
			GROUP BY CB16_ID
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':status', $status);

        return $command->query()->readAll();
    }
    

    
    
	
}
