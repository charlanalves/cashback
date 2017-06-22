<?php

namespace common\components;
use \yii\base\UserException;
/**
 * PaymentBaseComponent
 * 
 * */
class IuguComponent extends PaymentBaseComponent {
    
    const tbCliente = '\common\models\user';
    const tbEmpresa = '\common\models\CB04EMPRESA';
    const prefixCliente = '';
    const prefixEmpresa = 'CB04_';
    const STATUS_PAID = 'paid';
    
    
    protected function initialize()
    {
       require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));
       
       //teste
       \Iugu::setApiKey("67dfbb3560a62cb5cee9ca8730737a98");
       
       //producao
      // \Iugu::setApiKey("19f75e24d08d0dd3d01db446299a4ba6");
    }
    
    protected function prepareCreditCard($data)
    {
        
    }
    
    protected function processTransaction($data)
    {
        $this->lastResponse =  \Iugu_Charge::create($data);
        
        if (isset($this->lastResponse->error)) {     
          throw new UserException("O pagamento não foi autorizado tente novamente com outro cartão de crédito.");
        }
    }
    
 
    
    
   public function createAccount($dataApi) 
    {   
         $this->lastResponse = \Iugu_Marketplace::createAccount(['name'=> $dataApi['nomeConta']]); 
            
	      if (isset($dataApi['CPF_CNPJ']) && strlen($dataApi['CPF_CNPJ']) == '14') {
		      	$dataApi['price_range'] = "Mais que R$ 500,00";
			    $dataApi['physical_products'] = false;
			    $dataApi['business_type'] = "Serviços e produtos diversos";
			    $dataApi['automatic_transfer'] = true;
		  		$dataApi['person_type'] = 'Pessoa Jurídica';
		  		$dataApi['cnpj'] = $dataApi['CPF_CNPJ']; 
	  			  
	  		} else {
	  			  $dataApi['person_type'] = 'Pessoa Física';
	  			  $dataApi['cpf'] = $dataApi['CPF_CNPJ']; 
	  		}
	  		
	  		  \Iugu::setApiKey($this->lastResponse->user_token);
        
        	  \Iugu_Account::requestVerification($dataApi);  
	      
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
    
    
    
    
    public function doTranfer($trans) 
    {   
    	
    	foreach ($trans as $t){
    	 	$transaction  = \Yii::$app->db->beginTransaction();
    	 	
    		$transfer = \common\models\PAG04TRANSFERENCIAS::findOne($t['PAG04_ID']);
       		$transfer->PAG04_DT_DEP = date('Y-m-d');
       		
        	if ($transfer->save()){
        		 $this->lastResponse = \Iugu_Transfer::create($t);
        		 if (isset($this->lastResponse->errors)){
        		 	$transaction->commit();
        		 }else {
        		 	$transaction->rollback();
        		 }
        	}
    	}     
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
 
    
    public function fetchAccount() 
    {   
        $this->lastResponse = \Iugu_Account::fetch('6C65DFAABC5648B58D0E9D854EB52E04');      
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
    
	 public function AccountConfig() 
    {   
        $this->lastResponse = \Iugu_Account::configuration(['auto_withdraw' => true]);      
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
     
    
    protected function createSaveClienteAccount($atributos) 
    {
        $this->transaction = \Yii::$app->db->beginTransaction();
        
    	$cliente = new \common\models\CB02CLIENTE;
    	$cliente->setAttributes($atributos);
    	$cliente->save();
    	
        $atributos['id_cliente'] = $cliente->CB02_ID;
        $atributos['name'] = $cliente->CB02_NOME;
        $atributos['cpf_cnpj'] = $cliente->CB02_CPF_CNPJ;
        $atributos['email'] = $cliente->CB02_EMAIL;
        
        
        $model = new \frontend\models\SignupForm();
        $model->setAttributes($atributos);
        $user = $model->signup();
        
        $this->createAccount(['nomeConta' => $cliente->CB02_CPF_CNPJ]);
         
        $cliente->CB02_DADOS_API_TOKEN = json_encode($this->lastResponse);
        $cliente->CB02_COD_CONTA_VIRTUAL = $this->lastResponse->account_id;
        
        $cliente->save();
    }
    
    public function fetchUpdateDtDepInvoice(array $invoices) 
    {
    	foreach($invoices as $key => $invoice){
    		
     		$invoiceRet = \Iugu_Invoice::fetch($invoice['CB16_COD_TRANSACAO']);
     		
     		if (isset($invoiceRet->financial_return_dates)) {
     			if (isset($invoiceRet->financial_return_dates[0]->status)) {
	     			$pedido = \common\models\CB16PEDIDO::findOne($invoice['CB16_ID']);
	     			$pedido->CB16_DT_DEP = date('Y-m-d H:i:s');
	     			$pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_pago_trans_liberadas;
	     			$pedido->save();
     			}
     		}
    	}
    }
    
    
    protected function prepareDebitCard($data){}
    
    
}

?>
