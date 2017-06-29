<?php

namespace common\components;

use yii\base\Component;
use \common\models\PAG04TRANSFERENCIAS;


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
        
        exit(json_encode(['status' => $status, 'retorno' => utf8_encode($retorno), 'dev' => utf8_encode($dev), 'lastResponse'=> $this->lastResponse]));
    }
    
    public function globalCall($action, $data) 
    {
        $methodExists = method_exists($this, $action);
        
        if ( $methodExists == false ) {
            throw new \Exception("O método $action não existe");
        }
        
        return call_user_func_array([$this, $action], [$data]);
    }
    
    public function getDtPrevisao($prazoRecebimento, $dtAprovacao)
    {
    	return date('Y-m-d', strtotime("+".$prazoRecebimento." days", strtotime($dtAprovacao)));
    }
    

    public function criaTransferencias($params) 
    {   
        $pedidos = \common\models\CB16PEDIDO::getPedidoCompleto();
        $idUserMaster =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_MA');
        $idUserAdmin =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_SA');
        $idUserAdq =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CTADQ');

        $IuguSubAdmin =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('SB_PROD');
        
        $this->transaction  = \Yii::$app->db->beginTransaction();
        foreach ($pedidos as $pedido) {
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
        	$dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedido['CB16_DT_APROVACAO']);
        	$vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
        	$vlrAdmin = floor((($pedido['CB16_PERC_ADMIN']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	$vlrAdq = floor((($pedido['CB16_PERC_ADQ']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	
        	
        	// TRANSFÊNCIA MASTER TO EMPRESA
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $idUserMaster;
			$trans->PAG04_ID_USER_CONTA_DESTINO = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_VLR = $pedido['CB16_VALOR'];
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::M2E;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	
        	// TRANSFÊNCIA EMPRESA TO CLIENTE
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
			$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $pedido['ID_USER_CLIENTE'];
			$trans->PAG04_VLR = $vlrCliente;						
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2C;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	 
        	// TRANSFÊNCIA EMPRESA TO ADMIN
        	$trans = new \common\models\PAG04TRANSFERENCIAS;
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $idUserAdmin;
			$trans->PAG04_VLR = $vlrAdmin;			
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2ADM;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	// TRANSFÊNCIA EMPRESA TO ADQ
        	$trans = new \common\models\PAG04TRANSFERENCIAS;
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $idUserAdq;
			$trans->PAG04_VLR = $vlrAdq;			
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2ADQ;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	$pedido = \common\models\CB16PEDIDO::findOne($pedido['CB16_ID']);
        	$pedido->CB16_TRANS_CRIADAS = 1;
        	$pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_pago_trans_agendadas;
        	$pedido->save();
        }
    }
    
    public function criaTransferenciaPagSaldo($pedido) 
    {   
        $pedidos = \common\models\CB16PEDIDO::getPedidoCompletoByPedido($pedido);
        $idUserMaster =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_MA');
        $idUserAdmin =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_SA');
        $idUserAdq =  \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CTADQ');
        $now = date('Y-m-d H:i:s');
        
        foreach ($pedidos as $pedido) {
                
        	$dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedido['CB16_DT_APROVACAO']);
        	$vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
        	$vlrAdmin = floor((($pedido['CB16_PERC_ADMIN']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	$vlrAdq = floor((($pedido['CB16_PERC_ADQ']/100) * $pedido['CB16_VALOR']) * 100) / 100;
        	
        	// TRANSFÊNCIA CLIENTE TO EMPRESA
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_CLIENTE'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_VLR = $pedido['CB16_VALOR'];
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::C2E;
			$trans->PAG04_DT_PREV = $now;
			$trans->PAG04_DT_DEP = $now;
        	$trans->save();
        	
        	// TRANSFÊNCIA EMPRESA TO CLIENTE
        	$trans = new \common\models\PAG04TRANSFERENCIAS; 
			$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $pedido['ID_USER_CLIENTE'];
			$trans->PAG04_VLR = $vlrCliente;						
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2C;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	// TRANSFÊNCIA EMPRESA TO ADMIN
        	$trans = new \common\models\PAG04TRANSFERENCIAS;
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $idUserAdmin;
			$trans->PAG04_VLR = $vlrAdmin;			
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2ADM;
			$trans->PAG04_DT_PREV = $dtPrevisao;
        	$trans->save();
        	
        	// TRANSFÊNCIA EMPRESA TO ADQ
        	$trans = new \common\models\PAG04TRANSFERENCIAS;
        	$trans->PAG04_ID_PEDIDO = $pedido['CB16_ID'];		
			$trans->PAG04_ID_USER_CONTA_ORIGEM = $pedido['ID_USER_EMPRESA'];
			$trans->PAG04_ID_USER_CONTA_DESTINO = $idUserAdq;
			$trans->PAG04_VLR = $vlrAdq;			
			$trans->PAG04_TIPO = \common\models\PAG04TRANSFERENCIAS::E2ADQ;
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
