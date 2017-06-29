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
     //  \Iugu::setApiKey("67dfbb3560a62cb5cee9ca8730737a98");
       
       //producao
      \Iugu::setApiKey("19f75e24d08d0dd3d01db446299a4ba6");
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
    
 
    public function createAccount($accountName) 
    {   
    	$this->_createAccount($accountName);
    	
    	 if (strlen($accountName) == '18') {
    		$this->verifyAccount($accountName);
    	 }
    }
    
    
    public function _createAccount($accountName) 
    {   
    	 if (empty($accountName)) {
	          throw new UserException("Erro ao criar conta. CNPJ ou CPF não informado.");
    	 }
    	
         $this->lastResponse = \Iugu_Marketplace::createAccount(['name'=> $accountName]); 
          
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
    
   public function verifyAccount($dataApi) 
   {     
        $defaultData = [
            "price_range" => "Mais que R$ 500,00",
            "physical_products" => false,
            "automatic_transfer" => true,
         ];
	       	
        if (strlen($dataApi['CPF_CNPJ']) == '14') {
                 $defaultPJ = [
                    "person_type" => 'Pessoa Jurídica',
                    "business_type" => "Serviços e produtos diversos",
                 ];

            $dataApi = array_merge(array_merge($defaultPJ, $defaultData), $dataApi);

        } else {
            $defaultPF = [
                "business_type" => "Usuário com cashback",
                "person_type" => "Pessoa Física",
                "cpf" => $dataApi['CPF_CNPJ'],
             ];

             $dataApi = array_merge(array_merge($defaultPF, $defaultData), $dataApi);
        }

        \Iugu::setApiKey($this->lastResponse->user_token);

       $this->lastResponse->errors = \Iugu_Account::requestVerification($dataApi, $this->lastResponse->account_id);  
	      
        
        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao Verificar conta");
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
        
        $this->createAccount($cliente->CB02_CPF_CNPJ);
         
        $cliente->CB02_DADOS_API_TOKEN = json_encode($this->lastResponse);
        $cliente->CB02_COD_CONTA_VIRTUAL = $this->lastResponse->account_id;
        
        $cliente->save();
        
        $model = new \common\models\LoginForm();
        $model->cpf_cnpj = $cliente->CB02_CPF_CNPJ;
        $model->password = $atributos['password'];
        $model->loginCpfCnpj(); 
        
        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
        
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
