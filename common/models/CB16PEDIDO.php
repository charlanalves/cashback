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
	
 /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB16_ID' => Yii::t('app', 'Cb16  ID'),
            'CB16_EMPRESA_ID' => Yii::t('app', 'Cb16  Empresa  ID'),
            'CB16_USER_ID' => Yii::t('app', 'Cb16  User  ID'),
            'CB16_GATEWAY' => Yii::t('app', 'Cb16  Gateway'),
            'CB16_VALOR' => Yii::t('app', 'Cb16  Valor'),
            'CB16_FRETE' => Yii::t('app', 'Cb16  Frete'),
            'CB16_NUM_PARCELA' => Yii::t('app', 'Cb16  Num  Parcela'),
            'CB16_STATUS' => Yii::t('app', 'Cb16  Status'),
            'CB16_DT' => Yii::t('app', 'Cb16  Dt'),
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_ID_COMPRADOR', 'CB16_VALOR'], 'required'],
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_ID_COMPRADOR', 'CB16_ID_FORMA_PAG_EMPRESA', 'CB16_NUM_PARCELA', 'CB16_STATUS'], 'integer'],
            [['CB16_VALOR', 'CB16_FRETE'], 'number'],
            [['CB16_DT'], 'safe'],
            [['CB16_GATEWAY'], 'string', 'max' => 50],
            
            
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
    
	 public static function getPedidoCompleto($idPedido)
    {
        
        $sql = "
            SELECT * FROM CB16_PEDIDO
				JOIN CB18_VARIACAO_PEDIDO ON CB16_PEDIDO.CB16_ID = CB18_VARIACAO_PEDIDO.CB18_ID_PEDIDO
				JOIN CB06_VARIACAO ON CB06_VARIACAO.CB06_ID = CB18_VARIACAO_PEDIDO.CB18_ID_VARIACAO
				JOIN CB05_PRODUTO ON CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID
				JOIN CB04_EMPRESA ON  CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
				JOIN CB02_CLIENTE ON  CB16_PEDIDO.CB16_ID_COMPRADOR = CB02_CLIENTE.CB02_ID
				JOIN CB09_FORMA_PAGTO_EMPRESA ON  CB16_PEDIDO.CB16_ID_FORMA_PAG_EMPRESA = CB09_FORMA_PAGTO_EMPRESA.CB09_ID
				JOIN CB08_FORMA_PAGAMENTO ON CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG= CB08_FORMA_PAGAMENTO.CB08_ID
			WHERE CB16_PEDIDO.CB16_ID = :idPedido
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':idPedido', $idPedido);

        return $command->query()->readAll();
    }
    

    
    
	
}
