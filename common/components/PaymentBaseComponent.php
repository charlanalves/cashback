<?php

namespace common\components;

use yii\base\Component;


/**
 * PaymentBaseComponent
 * 
 * */
abstract class PaymentBaseComponent extends Component {
    protected $lastResponse;
    public $transaction = null;


    abstract protected function initialize();
    
    abstract protected function prepareCreditCard($data);
    
    abstract protected function prepareDebitCard($data);
    
    abstract protected function processTransaction($data);
    
    public function __construct($config = [])
    {
        $this->initialize();
        
        parent::__construct($config);
    }
 
    public function exec($metodo, $params)
    {
        if (is_null($params)) {
            $params = Yii::$app->request->post();
        }

        $status = true;
        $dev = '';
        
        try {
           $retorno = $this->globalCall($metodo, $params);
        
        } catch (\Exception $e) {
            $status = false;
            //$this->log();
            $retorno = 'Ocorreu um erro interno. Tente novamente em alguns minutos.';
            $dev = $e->getMessage();
            
            if ( $e instanceof \yii\base\UserException) {
                $retorno = $e->getMessage();
            }             
        }

        if ($status) {
        	return $this->lastResponse->attributes;
        } 
        
        throw new \Exception($retorno);
        
    }
    public function execute($metodo, $params)
    {
        if (is_null($params)) {
            $params = Yii::$app->request->post();
        }

        $status = true;
        $dev = '';
        
        try {
           $retorno = $this->globalCall($metodo, $params);
           
           if ($this->transaction instanceof \yii\db\Transaction) {
                $this->transaction->commit();
           }
        } catch (\Exception $e) {
            $status = false;
           // $this->log();
            $retorno = 'Ocorreu um erro interno. Tente novamente em alguns minutos.';
            $dev = $e->getMessage();
            
            if ($this->transaction instanceof \yii\db\Transaction) {
                $this->transaction->rollBack();
            }
            
            if ( $e instanceof \yii\base\UserException) {
                $retorno = $e->getMessage();
            } 
        }
        
        exit(json_encode(['status' => $status, 'retorno' => $retorno, 'dev' => $dev, 'lastResponse'=> $this->lastResponse]));
    }
    
    public function globalCall($action, $data) 
    {
        $methodExists = method_exists($this, $action);
        
        if ( $methodExists == false ) {
            throw new \Exception("O método $action não existe");
        }
        
        call_user_func_array([$this, $action], [$data]);
    }
    
    public function getDtPrevisao($prazoRecebimento, $dtAprovacao)
    {
    	return date('Y-m-d', strtotime("+".$prazoRecebimento." days", strtotime($dtAprovacao)));
    }
    

    public function criaTransferencias($params) 
    {   
        $pedidos = \common\models\CB16PEDIDO::getPedidoCompleto();
        $IuguMaster =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('CT_DEV');
        $IuguSubAdmin =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('SB_PROD');
        
        $this->transaction  = \Yii::$app->db->beginTransaction();
        foreach ($pedidos as $pedido) {
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
        	$dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedido['CB16_DT_APROVACAO']);
        	$vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
        	$vlrAdmin = floor((($pedido['CB16_PERC_ADMIN']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	$vlrAdq = floor((($pedido['CB16_PERC_ADQ']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	
        	// TRANSF�NCIA MASTER TO CLIENTE
			$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO = $pedido['CB02_COD_CONTA_VIRTUAL'];
			$trans->PAG04_VLR = $vlrCliente;						
			$trans->PAG04_TIPO = 1;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	 
        	// TRANSF�NCIA MASTER TO ADMIN
        	$trans = new \common\models\PAG04TRANSFERENCIAS;
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO = $IuguSubAdmin;
			$trans->PAG04_VLR = $vlrAdmin;			
			$trans->PAG04_TIPO = 2;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	
        	// TRANSF�NCIA MASTER TO EMPRESA
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO =  $IuguSubAdmin;
			$trans->PAG04_VLR = $pedido['CB16_VALOR'] - $vlrCliente - $vlrAdmin - $vlrAdq;
			$trans->PAG04_TIPO = 3;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	$pedido = \common\models\CB16PEDIDO::findOne($pedido['CB16_ID']);
        	$pedido->CB16_TRANS_CRIADAS = 1;
        	$pedido->save();
        }
    }
    
    public function saveAttempt($idPedido, $status = 0)
    { 
    	$attempt = new \common\models\PAG05_TENTATIVAS_COMPRA;
	
		$attempt->PAG05_ID_PEDIDO = $idPedido;
		$attempt->PAG05_STATUS = $status;
		$attempt->PAG05_DESC_ERRO = $this->error;
		$attempt->PAG05_ERRO_COMPLETO_JSON = json_encode($this->fullError);
		$attempt->save();
    }	
    
    
}

?>
