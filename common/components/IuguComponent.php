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
       //\Iugu::setApiKey("19f75e24d08d0dd3d01db446299a4ba6");
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
    
    public function createAccount() 
    {   
        $this->lastResponse = \Iugu_Marketplace::createAccount(['name'=>'SubMaster']);      
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
    
    protected function createSaveClienteAccount($atributos) 
    {
        $this->transaction = \Yii::$app->db->beginTransaction();
        
        $model = new \frontend\models\SignupForm();
        $model->setAttributes($atributos);
        $user = $model->signup();
        
        $this->createAccount();
        
        $user->DADOS_API_TOKEN = json_encode($this->lastResponse);
        $user->ID_CONTA_IUGU = $this->lastResponse->account_id;
        
        $user->save();
        
        return $user->attributes;
    }
    
    public function fetchUpdateDtDepInvoice(array $invoices) 
    {
    	foreach($invoices as $key => $invoice){
    		
     		$invoiceRet = \Iugu_Invoice::fetch($invoice['CB16_COD_TRANSACAO']);
     		
     		if ($invoiceRet->status == self::STATUS_PAID) {
     			$pedido = \common\models\CB16PEDIDO::findOne($invoice['CB16_ID']);
     			$pedido->CB16_DT_DEP = date('Y-m-d H:i:s');
     			$pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_liberado;
     			$pedido->save();
     		}
    	}
    }
    
    
    protected function prepareDebitCard($data){}
    
    
}

?>
