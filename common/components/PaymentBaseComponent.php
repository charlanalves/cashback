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
        if (($pedidos = \common\models\CB16PEDIDO::getPedidoCompleto())) {
        
            $this->transaction = \Yii::$app->db->beginTransaction();
            
            foreach ($pedidos as $pedido) {

                $idCliente = $pedido['ID_USER_CLIENTE'];
                $idEmpresa = $pedido['ID_USER_EMPRESA'];
                $idPedido = $pedido['CB16_ID'];
                $vlrPedido = $pedido['CB16_VALOR'];
                $vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
                $vlrAdmin = floor((($pedido['CB16_PERC_ADMIN']/100) * $pedido['CB16_VALOR']) * 100) / 100;
                $vlrAdq = floor((($pedido['CB16_PERC_ADQ']/100) * $pedido['CB16_VALOR']) * 100) / 100;
                $dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedido['CB16_DT_APROVACAO']);

                $trans = new \common\models\PAG04TRANSFERENCIAS;

                // TRANSFÊNCIA MASTER TO EMPRESA
                $trans->createM2E($idEmpresa, $vlrPedido, $dtPrevisao, $idPedido);

                // TRANSFÊNCIA MASTER TO CLIENTE
                $trans->createM2C($idCliente, $vlrCliente, $idPedido);

                // TRANSFÊNCIA EMPRESA TO MASTER
                $trans->createE2M($idEmpresa, $vlrCliente, $dtPrevisao, $idPedido);

                // TRANSFÊNCIA EMPRESA TO ADMIN
                $trans->createE2ADM($idEmpresa, $vlrAdmin, $dtPrevisao, $idPedido);

                // TRANSFÊNCIA EMPRESA TO ADQ
                $trans->createE2ADQ($idEmpresa, $vlrAdq, $dtPrevisao, $idPedido);

                $pedido = \common\models\CB16PEDIDO::findOne($idPedido);
                $pedido->CB16_TRANS_CRIADAS = 1;
                $pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_pago_trans_agendadas;
                $pedido->save();
                
            }
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
