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
    const apiTokenProd = '19f75e24d08d0dd3d01db446299a4ba6';
    const apiTokenTest = '67dfbb3560a62cb5cee9ca8730737a98';
    
    
    protected function initialize()
    {
       require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));
       
       //teste
     //  \Iugu::setApiKey("67dfbb3560a62cb5cee9ca8730737a98");
       
       //producao
      \Iugu::setApiKey(self::apiTokenProd);
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
    
    private function saveApiCod($model) 
    {
        $model->CB04_DADOS_API_TOKEN = json_encode($this->lastResponse);
        $model->CB04_COD_CONTA_VIRTUAL = $this->lastResponse->account_id;
        $model->save();
    }
    
    private function saveCompanyLogo($model, $id)
    {
         if (!empty($_FILES['CB04_URL_LOGOMARCA']['name'])) {
            
            $infoFile = \Yii::$app->u->infoFile($_FILES['CB04_URL_LOGOMARCA']);
            if($infoFile['family'] == 'image') {
                $infoFile['path'] = 'img/fotos/estabelecimento/';
                $infoFile['newName'] = uniqid("logo_" . $id . "_") . '.' . $infoFile['ex'];

                $file = \yii\web\UploadedFile::getInstanceByName('CB04_URL_LOGOMARCA');
                $pathCompleto = $infoFile['path'] . $infoFile['newName'];

                if ($file->saveAs($pathCompleto)) {
                    if(!empty($model->CB04_URL_LOGOMARCA)) {
                        @unlink($model->CB04_URL_LOGOMARCA);
                    }
                    $model->setAttribute('CB04_URL_LOGOMARCA', $pathCompleto);
                    $model->save();
                }
            }
        }
    }
    private function createCompanyUser($empresa)
    {        
        $user = new \common\models\User;
        $user->cpf_cnpj = $empresa->CB04_CNPJ;
        $user->name = $empresa->CB04_NOME;
        $user->user_principal = 1;
        $user->id_company = $empresa->CB04_ID;
        $user->email = $empresa->CB04_EMAIL;
        $user->username = $empresa->CB04_CNPJ;
        $user->setPassword(123456);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = 'estabelecimento';
        $assignment->user_id = (string) $user->id;
        $assignment->save();
    }
    
   public function createCompanyAccount($dataApi) 
   {
       $data = $dataApi['data'];
       $model = $dataApi['model'];
       $id = $dataApi['id'];
       
       $this->_createAccount($data['cnpj']);
       $this->verifyAccount($data);
       $this->saveApiCod($model);
       $this->saveCompanyLogo($model, $id);
       $this->createCompanyUser($model);
   }
    public function createAccount($accountName) 
    {   
    	$this->_createAccount($accountName);
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
	       	
        if (isset($dataApi['cnpj'])) {
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
        $data['data'] = $dataApi;
       $this->lastResponse = \Iugu_Account::requestVerification($data, $this->lastResponse->account_id);  
	      
    
        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao Verificar conta");
        }
    	
    }
    
    public function verifyClientAccount($param)
    {
       $data = [
                "data" =>
                [
                    "price_range" => "Mais que R$ 500,00",
                    "physical_products" => false,
                    "business_type" => "Cliente CashBack",
                    "automatic_transfer" => true,
                    "person_type" => 'Pessoa Física',
                    "cpf" => '09480912660',
                    "name" => $param['CB02_NOME'], 
                    "address" => 'Rua inexisteste', 
                    "cep"=> '31650-000',  
                    "city" => 'BH', 
                    "state" => 'MG', 
                    "telephone" => '31999999999', 
                    "bank" => 'Itaú', 
                    "bank_ag" => $param['CB03_AGENCIA'], 
                    "account_type" => ($param['CB03_TP_CONTA']) ? 'Poupança': 'Corrente', 
                    "bank_cc" => $param['CB03_NUM_CONTA']
               ]
	    ];  
       $data2 = [
                  
                    "bank" => 'Itaú', 
                    "bank_ag" => '0925', 
                    "bank_cc" => '02159-4',
                    "account_type" => ($param['CB03_TP_CONTA']) ? 'cp': 'cc', 
                    "automatic_validation" => true
	    ];  
       
         $token = json_decode($param['CB02_DADOS_API_TOKEN']);
         \Iugu::setApiKey($token->user_token);
         $this->lastResponse = \Iugu_Account::bank_verification($data2);  
         
         if (isset($this->lastResponse->last_verification_request_status)){
            if ($this->lastResponse->last_verification_request_status != 'pending') {
                 $this->lastResponse = \Iugu_Account::requestVerification($data, $token->account_id);  
                  
                   if (isset($this->lastResponse->errors)) {
                        throw new UserException("Erro ao Verificar a conta.");
                   }
                   
                   $trans = \common\models\PAG04TRANSFERENCIAS::findOne($param['PAG04_ID']);
                   $trans->PAG04_DT_DEP = date('Y-m-d H:i:s');
                   $trans->save();
            }
             
         }
         
        
        
    }
    
    
    public function doTranfer($amount, $accountId) 
    {   
        
        \Iugu::setApiKey(self::apiTokenProd);
        
        $data['receiver_id'] = $accountId;
        $data['amount_cents'] = $amount * 100;
        
                
    	$this->lastResponse = \Iugu_Transfer::create($data);  
        
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
        
        $user = new \common\models\User;
        $user->cpf_cnpj = $cliente->CB02_CPF_CNPJ;
        $user->name = $cliente->CB02_NOME;       
        $user->id_cliente = $cliente->CB02_ID;
        $user->email = $cliente->CB02_EMAIL;
        $user->username = $cliente->CB02_CPF_CNPJ;
        $user->setPassword($atributos['password']);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = 'cliente';
        $assignment->user_id = (string) $user->id;
        $assignment->save();
        
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
    
    public function realizaSaques() 
    {   

        if (($saquesPendentes = \common\models\CB16PEDIDO::getSaquesPendentes())) {

            $this->transaction = \Yii::$app->db->beginTransaction();
            
            foreach ($saquesPendentes as $sp) {

                $token = json_decode($sp['CB02_DADOS_API_TOKEN']);
                $this->verifyClientAccount($sp);
                $this->doTranfer($sp['PAG04_VLR'], $token->account_id);
            }
        }
    
    }
    
    protected function prepareDebitCard($data){}
    
    
}

?>
