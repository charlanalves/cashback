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
    public $status_pedido = [1 => 'CANCELADO', 10 => 'AGUARDANDO PAGAMENTO', 20 => 'BAIXADO', 30 => 'PAGO', 40 => 'PAGO', 50 => 'PAGO', 60 => 'PAGO'];
    const status_cancelado = 1;
    const status_aguardando_pagamento = 10;
    const status_baixado = 20;
    const status_pago = 30;
    const status_pago_trans_agendadas = 40;
    const status_pago_trans_liberadas = 50;
    const status_pago_trans_realizadas = 60;
   
    // status da entrega
    public $status_delivery = [0 => 'Entrega cancelada', 1 => 'Aguardando entrega', 2 => 'Saiu para entrega', 3 => 'Entregue'];    
    const status_delivery_cancelado = 0;
    const status_delivery_aguardando = 1;
    const status_delivery_saiu_entrega = 2;
    const status_delivery_entregue = 3;
    
    const trans_nao_criadas = 0;
    const trans_criadas = 1;

    const SCENARIO_ATUALIZA_PEDIDO_PAGO = 'SCENARIO_ATUALIZA_PEDIDO_PAGO';
    const SCENARIO_DELIVERY_ADDRESS = 'SCENARIO_DELIVERY_ADDRESS';
    
   
	
 /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB16_ID' => 'Pedido',
            'CB16_TRANS_CRIADAS' => 'Cb16  Trans  Criadas',
            'CB16_EMPRESA_ID' => 'Cb16  Empresa  ID',
            'CB16_ID_COMPRADOR' => 'Cb16  Id  Comprador',
            'CB16_USER_ID' => 'Cliente',
            'CB16_ID_FORMA_PAG_EMPRESA' => 'Cb16  Id  Forma  Pag  Empresa',
            'CB16_VALOR' => 'Cb16  Vlr',
            'CB16_PERC_ADMIN' => 'Perc. Admin',
            'CB16_PERC_ADQ' => 'Perc. Adq',
            'CB16_PERC_REP' => 'Perc. Representante',
            'CB16_PERC_FUN' => 'Perc. Funcionário',
            'CB16_VLR_CB_TOTAL' => 'Cb16  Vlr  Cb  Total',
            'CB16_FRETE' => 'Cb16  Frete',
            'CB16_STATUS' => 'Cb16  Status',
            'CB16_DT' => 'Cb16  Dt',
            'CB16_DT_APROVACAO' => 'Data',
            'CB16_FORMA_PAG' => 'Cb16  Forma  Pag',
            'CB16_CARTAO_TOKEN' => 'Cb16  Cartao  Token',
            'CB16_CARTAO_NUM_PARCELA' => 'Cb16  Cartao  Num  Parcela',
            'CB16_CARTAO_VLR_PARCELA' => 'Cb16  Cartao  Vlr  Parcela',
            'CB16_COMPRADOR_NOME' => 'Cb16  Comprador  Nome',
            'CB16_COMPRADOR_EMAIL' => 'Cb16  Comprador  Email',
            'CB16_COMPRADOR_CPF' => 'Cb16  Comprador  Cpf',
            'CB16_COMPRADOR_TEL_DDD' => 'Cb16  Comprador  Tel  Ddd',
            'CB16_COMPRADOR_TEL_NUMERO' => 'Telefone',
            'CB16_COMPRADOR_END_LOGRADOURO' => 'Logradouro',
            'CB16_COMPRADOR_END_NUMERO' => 'Numero',
            'CB16_COMPRADOR_END_BAIRRO' => 'Bairro',
            'CB16_COMPRADOR_END_CEP' => 'CEP',
            'CB16_COMPRADOR_END_CIDADE' => 'Cidade',
            'CB16_COMPRADOR_END_UF' => 'UF',
            'CB16_COMPRADOR_END_PAIS' => 'Pais',
            'CB16_COMPRADOR_END_COMPLEMENTO' => 'Complemento',
            'CB16_STATUS_DELIVERY' => 'Status da entrega',
            'STATUS_DELIVERY' => 'Situação',
            'ENDERECO_COMPLETO' => 'Endereço',
            'CB17_NOME_PRODUTO' => 'Pedido',
            'CB16_ORIGEM' => 'Origem'
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DELIVERY_ADDRESS] = ['CB16_COMPRADOR_TEL_NUMERO','CB16_COMPRADOR_END_CEP', 'CB16_COMPRADOR_END_LOGRADOURO', 'CB16_COMPRADOR_END_NUMERO', 'CB16_COMPRADOR_END_BAIRRO', 'CB16_COMPRADOR_END_CIDADE', 'CB16_COMPRADOR_END_UF'];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {  
        return array_replace_recursive(parent::rules(),
         [
            [['CB16_TRANS_CRIADAS', 'CB16_EMPRESA_ID', 'CB16_ID_FORMA_PAG_EMPRESA', 'CB16_STATUS', 'CB16_CARTAO_NUM_PARCELA', 'CB16_STATUS_DELIVERY'], 'integer'],
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_VALOR', 'CB16_VLR_CB_TOTAL'], 'required'],
            [['CB16_ID_FORMA_PAG_EMPRESA', 'CB16_FORMA_PAG', 'CB16_PERC_ADMIN', 'CB16_PERC_ADQ', 'CB16_STATUS', 'CB16_DT_APROVACAO'], 'required' ,'on' => self::SCENARIO_ATUALIZA_PEDIDO_PAGO , 'message'=>'O {attribute} é obrigatório'],
            [['CB16_VALOR', 'CB16_PERC_ADMIN', 'CB16_PERC_ADQ', 'CB16_VLR_CB_TOTAL', 'CB16_FRETE', 'CB16_CARTAO_VLR_PARCELA', 'CB16_PERC_REP', 'CB16_PERC_FUN'], 'number'],
            [['CB16_DT', 'CB16_DT_APROVACAO', 'CB16_STATUS_DELIVERY'], 'safe'],
            [['CB16_CARTAO_TOKEN'], 'string'],
            [['CB16_FORMA_PAG', 'CB16_ORIGEM'], 'string', 'max' => 50],
            [['CB16_COMPRADOR_NOME', 'CB16_COMPRADOR_EMAIL', 'CB16_COMPRADOR_CPF', 'CB16_COMPRADOR_TEL_DDD', 'CB16_COMPRADOR_TEL_NUMERO', 'CB16_COMPRADOR_END_LOGRADOURO', 'CB16_COMPRADOR_END_NUMERO', 'CB16_COMPRADOR_END_BAIRRO', 'CB16_COMPRADOR_END_CEP', 'CB16_COMPRADOR_END_CIDADE', 'CB16_COMPRADOR_END_UF'], 'string', 'max' => 100],
            [['CB16_COMPRADOR_END_PAIS'], 'string', 'max' => 2],
            [['CB16_COMPRADOR_END_COMPLEMENTO'], 'string', 'max' => 500],             
            [['CB16_COMPRADOR_TEL_NUMERO','CB16_COMPRADOR_END_CEP', 'CB16_COMPRADOR_END_LOGRADOURO', 'CB16_COMPRADOR_END_NUMERO', 'CB16_COMPRADOR_END_BAIRRO', 'CB16_COMPRADOR_END_CIDADE', 'CB16_COMPRADOR_END_UF'], 'required' ,'on' => self::SCENARIO_DELIVERY_ADDRESS , 'message'=>'O {attribute} é obrigatório'],
        ]);
    }
  
    public static function getPedido($pedido, $usuario)
    {
        
        $sql = "SELECT CB16_PEDIDO.*, CB06_VARIACAO.*, CB17_PRODUTO_PEDIDO.*, CB04_EMPRESA.*, CB14_FOTO_PRODUTO.CB14_URL 
                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN CB06_VARIACAO ON(CB06_ID = CB17_VARIACAO_ID)
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
        
        $sql = "SELECT CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, user.*, DATE_FORMAT(CB16_DT,'%d/%m/%Y') as CB16_DT, CB14_URL AS IMG,CB06_DISTRIBUICAO, 
                CASE CB16_STATUS 
                    WHEN " . self::status_cancelado . " THEN 'Cancelado'
                    WHEN " . self::status_aguardando_pagamento . " THEN 'Aguardando pagamento'
                    WHEN " . self::status_baixado . " THEN 'Utilizado'
                    WHEN " . self::status_pago . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_agendadas . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_liberadas . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_realizadas . " THEN 'Pago'
                    ELSE CB16_STATUS END AS STATUS,
                CASE CB16_STATUS_DELIVERY 
                    WHEN " . self::status_delivery_cancelado . " THEN 'Entrega cancelada'
                    WHEN " . self::status_delivery_aguardando . " THEN 'Aguardando entrega'
                    WHEN " . self::status_delivery_saiu_entrega . " THEN 'Saiu para entrega'
                    WHEN " . self::status_delivery_entregue . " THEN 'Entregue'
                    ELSE '' END AS STATUS_DELIVERY

                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN CB06_VARIACAO ON(CB06_ID = CB17_VARIACAO_ID)
                INNER JOIN user ON(user.id = CB16_USER_ID)
                LEFT JOIN (SELECT MAX(CB14_ID), CB14_PRODUTO_ID, CB14_URL FROM CB14_FOTO_PRODUTO GROUP BY CB14_PRODUTO_ID) CB14_FOTO_PRODUTO ON(CB14_PRODUTO_ID = CB17_PRODUTO_ID)
                WHERE 1 = 1 " . (!$key ? "" : " AND auth_key = :usuario") . (!$empresa ? "" : " AND CB16_EMPRESA_ID = :empresa") . (!$pedido ? "" : " AND CB16_ID = :pedido") . "
                ORDER BY CB16_STATUS DESC, CB16_DT DESC";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        if ($key) {
            $command->bindValue(':usuario', $key);
        }
        if ($empresa) {
            $command->bindValue(':empresa', $empresa);
        }
        if ($pedido) {
            $command->bindValue(':pedido', $pedido);
        }
        return $command->query()->readAll();
        
    }
    
    public static function getSaquesPendentes($id = '')
    {
        $and = '';
        if (!empty($id)) {
            $and = " AND PAG04_TRANSFERENCIAS.PAG04_ID = :ID";
        }
        
        $sql = "
            SELECT * FROM PAG04_TRANSFERENCIAS
            JOIN user on user.id = PAG04_TRANSFERENCIAS.PAG04_ID_USER_CONTA_ORIGEM
            JOIN CB03_CONTA_BANC ON CB03_CONTA_BANC.CB03_USER_ID = user.id
            JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
            WHERE 
            PAG04_TRANSFERENCIAS.PAG04_TIPO = 'V2B' AND
            PAG04_TRANSFERENCIAS.PAG04_STATUS IS NULL AND
            PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO IS NULL
            
            $and
        ";
        
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        if (!empty($id)) {
            $command->bindValue(':ID', $id);
        }
        return $command->query()->readAll();
    }
    
    // utilizado tbm para gerar as transferencias
    public static function getPedidoCompleto()
    {
        
        $sql = "
             SELECT *,USER_EMPRESA.id AS ID_USER_EMPRESA, user.id AS ID_USER_CLIENTE FROM CB16_PEDIDO
				JOIN CB17_PRODUTO_PEDIDO ON CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID = CB16_PEDIDO.CB16_ID  
				JOIN CB06_VARIACAO ON CB06_VARIACAO.CB06_ID = CB17_PRODUTO_PEDIDO.CB17_VARIACAO_ID
				JOIN CB05_PRODUTO ON CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID
				JOIN CB04_EMPRESA ON  CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN user ON  CB16_PEDIDO.CB16_USER_ID = user.id
		 	    JOIN VIEW_USER_ESTABELECIMENTO AS USER_EMPRESA ON USER_EMPRESA.id_company = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
				JOIN CB09_FORMA_PAGTO_EMPRESA ON  CB16_PEDIDO.CB16_ID_FORMA_PAG_EMPRESA = CB09_FORMA_PAGTO_EMPRESA.CB09_ID
				JOIN CB08_FORMA_PAGAMENTO ON CB08_FORMA_PAGAMENTO.CB08_ID = CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG
			WHERE CB16_PEDIDO.CB16_TRANS_CRIADAS = :transCriadas
			AND CB16_PEDIDO.CB16_STATUS >= :status
			AND CB16_PEDIDO.CB16_DT_APROVACAO IS NOT NULL
			
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
		 	    JOIN VIEW_USER_ESTABELECIMENTO AS USER_EMPRESA ON USER_EMPRESA.id_company = CB16_PEDIDO.CB16_EMPRESA_ID
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
			WHERE CB16_PEDIDO.CB16_STATUS >= :status
			GROUP BY CB16_ID
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':status', $status);

        return $command->query()->readAll();
    }
    
    public static function verificaPendenciaDeliveryAddress($pedido)
    {
        $sql = "
            SELECT CB16_PEDIDO.* 
            FROM CB16_PEDIDO 
            INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_ID = CB17_PEDIDO_ID)
            INNER JOIN CB06_VARIACAO ON(CB06_ID = CB17_VARIACAO_ID)
            WHERE CB16_ID = :pedido AND CB06_DISTRIBUICAO = 1";

        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':pedido', $pedido);
        $r = $command->queryOne();
        return $r ? !empty($r['CB16_COMPRADOR_END_LOGRADOURO']) : true;
    }
    
    
    public static function getPedidoDelivery($empresa = "", $pedido = "")
    {
        
        $sql = "SELECT CB16_PEDIDO.CB16_ID AS ID, CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, user.*, DATE_FORMAT(CB16_DT,'%d/%m/%Y') as CB16_DT,
                DATE_FORMAT(CB16_DT_APROVACAO,'%d/%m/%Y %H:%i') as CB16_DT_APROVACAO, 
                CONCAT(CB16_COMPRADOR_END_LOGRADOURO , ', ', CB16_COMPRADOR_END_NUMERO, ' - ', CB16_COMPRADOR_END_BAIRRO, ' - ', CB16_COMPRADOR_END_CIDADE, '/', CB16_COMPRADOR_END_UF, '<br />', CB16_COMPRADOR_END_COMPLEMENTO) AS ENDERECO_COMPLETO,
                CASE CB16_STATUS 
                    WHEN " . self::status_cancelado . " THEN 'Cancelado'
                    WHEN " . self::status_aguardando_pagamento . " THEN 'Aguardando pagamento'
                    WHEN " . self::status_baixado . " THEN 'Utilizado'
                    WHEN " . self::status_pago . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_agendadas . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_liberadas . " THEN 'Pago'
                    WHEN " . self::status_pago_trans_realizadas . " THEN 'Pago'
                    ELSE CB16_STATUS END AS STATUS,
                CASE CB16_STATUS_DELIVERY 
                    WHEN " . self::status_delivery_cancelado . " THEN 'Entrega cancelada'
                    WHEN " . self::status_delivery_aguardando . " THEN 'Aguardando entrega'
                    WHEN " . self::status_delivery_saiu_entrega . " THEN 'Saiu para entrega'
                    WHEN " . self::status_delivery_entregue . " THEN 'Entregue'
                    ELSE '' END AS STATUS_DELIVERY,
                CONCAT('img/editar.png^Alterar status da entrega^javascript:alterarStatusDelivery(', CB16_PEDIDO.CB16_ID, ')^_self;') AS editar

                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN CB06_VARIACAO ON(CB06_ID = CB17_VARIACAO_ID)
                INNER JOIN user ON(user.id = CB16_USER_ID)
                WHERE CB16_STATUS >= " . self::status_pago . " AND CB06_DISTRIBUICAO = 1 AND CB16_STATUS_DELIVERY <> 0  " . (!$empresa ? "" : " AND CB16_EMPRESA_ID = :empresa") . (!$pedido ? "" : " AND CB16_ID = :pedido") . "
                ORDER BY CB16_DT, CB16_STATUS DESC";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        
        if ($empresa) {
            $command->bindValue(':empresa', $empresa);
        }
        if ($pedido) {
            $command->bindValue(':pedido', $pedido);
        }
        return $command->query()->readAll();
        
    }
    
	
    /**
     * @inheritdoc
     */
    public function gridQueryDeliveryMain() {
        $id_company = \Yii::$app->user->identity->id_company;
        return $this->getPedidoDelivery($id_company);
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsDeliveryMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB16_ID'], 'align' => 'center', 'width' => '70', 'type' => 'ro', 'id' => 'CB16_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB16_DT_APROVACAO'], 'align' => 'center', 'width' => '120', 'type' => 'ro', 'id' => 'CB16_DT_APROVACAO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB16_USER_ID'], 'align' => 'left', 'width' => '200', 'type' => 'ro', 'id' => 'name'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB16_COMPRADOR_TEL_NUMERO'], 'align' => 'center', 'width' => '100', 'type' => 'ro', 'id' => 'CB16_COMPRADOR_TEL_NUMERO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['ENDERECO_COMPLETO'], 'align' => 'left', 'width' => '250', 'type' => 'ro', 'id' => 'ENDERECO_COMPLETO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB17_NOME_PRODUTO'], 'align' => 'left', 'width' => '200', 'type' => 'ro', 'id' => 'CB17_NOME_PRODUTO'], 'filter' => ['title' => '#text_filter']],            
            ['sets' => ['title' => $al['STATUS_DELIVERY'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'STATUS_DELIVERY'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'EDITAR', 'align' => 'center', 'width' => '70', 'type' => 'img', 'id' => 'editar'], 'filter' => ['title' => '']],
        ];
    }
}
