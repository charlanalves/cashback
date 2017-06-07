<?php

namespace common\components;

use yii\base\Component;


/**
 * PaymentBaseComponent
 * 
 * */
abstract class PaymentBaseComponent extends Component {
    protected $lastResponse;
    protected $transaction = null;


    abstract protected function initialize();
    
    abstract protected function prepareCreditCard($data);
    
    abstract protected function prepareDebitCard($data);
    
    abstract protected function processTransaction($data);
    
    public function __construct($config = [])
    {
        $this->initialize();
        
        parent::__construct($config);
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
            $this->log();
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
            throw new \Exception("O mÃ©todo $action nÃ£o existe");
        }
        
        call_user_func_array([$this, $action], [$data]);
    }
    
    public function getDtPrevisao($prazoRecebimento, $dtAprovacao)
    {
    	return date('Y-m-d', strtotime("+".$prazoRecebimento." days", strtotime($dtAprovacao)));
    }
    
  
    public function criaTransferencias($params) 
    {
   	   $params['idPedido'] = 5;
   	    
        $pedidos = \common\models\CB16PEDIDO::getPedidoCompleto($params['idPedido']);
        $IuguMaster =  \app\common\models\SYS01PARAMETROSGLOBAIS::getValor('CT_DEV');
        $IuguSubAdmin =  \app\common\models\SYS01PARAMETROSGLOBAIS::getValor('SB_DEV');
        
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($pedidos as $pedido) {
        	$transacao = new \app\common\models\PAG01_TRANSACAO; 
        	$dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedidos['CB16_DT_APROVACAO']);
        	$vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
        	$vlrAdmin = floor((($pedido['CB16_PERC_ADMIN']/100) * $pedido['CB16_VLR']) * 100) / 100;
        	$vlrAdq = floor((($pedido['CB16_PERC_ADQ']/100) * $pedido['CB16_VLR']) * 100) / 100;
        	
        	// TRANSFÊNCIA MASTER TO CLIENTE
			$trans->PAG04_ID_PEDIDO = $pedidos['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO = $pedido['CB02_COD_CONTA_VIRTUAL'];
			$trans->PAG04_VLR = $vlrCliente;
			$trans->PAG04_DT_PREV = $dtPrevisao;			
			$trans->PAG04_TIPO = 1;
        	$trans->save();
        	
        	// TRANSFÊNCIA MASTER TO ADMIN
        	$trans->PAG04_ID_PEDIDO = $pedidos['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO = $IuguSubAdmin;
			$trans->PAG04_VLR = $vlrAdmin;
			$trans->PAG04_DT_PREV = $dtPrevisao;			
			$trans->PAG04_TIPO = 2;
        	$trans->save();
        	
        	
        	// TRANSFÊNCIA MASTER TO EMPRESA
        	$trans->PAG04_ID_PEDIDO = $pedidos['CB16_ID'];		
			$trans->PAG04_COD_CONTA_ORIGEM = $IuguMaster;
			$trans->PAG04_COD_CONTA_DESTINO =  $pedido['CB04_COD_CONTA_VIRTUAL'];;
			$trans->PAG04_VLR = $pedido['CB16_VLR'] - $vlrCliente - $vlrAdmin - $vlrAdq;
			$trans->PAG04_DT_PREV = $dtPrevisao			
			$trans->PAG04_TIPO = 3;
        	$trans->save();
        }
    }
    
    public function log(){ }
    
}

?>
