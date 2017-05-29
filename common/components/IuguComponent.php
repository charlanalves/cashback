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
    
    
    protected function initialize()
    {
       require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));
       
       
    //   \Iugu::setApiKey("67dfbb3560a62cb5cee9ca8730737a98");
       
       \Iugu::setApiKey("19f75e24d08d0dd3d01db446299a4ba6");
    }
    
    protected function prepareCreditCard($data)
    {
        
    }
    
    protected function processTransaction($data)
    {
        
    }
    
    private function criarConta() 
    {   
        $this->lastResponse = \Iugu_Marketplace::createAccount();      
        
        if (isset($this->lastResponse->errors)) {
          throw new UserException("Erro ao criar conta.");
        }
    }
    
    protected function criarSalvarContaCliente($atributos) 
    {
        $this->transaction = \Yii::$app->db->beginTransaction();
        
        $model = new \frontend\models\SignupForm();
        $model->setAttributes($atributos);
        $user = $model->signup();
        
        $this->criarConta();
        
        $user->DADOS_API_TOKEN = json_encode($this->lastResponse);
        $user->ID_CONTA_IUGU = $this->lastResponse->account_id;
        
        $user->save();
        
        return $user->attributes;
    }
    
    private function criarSalvarContaEmpresa($atributos) 
    {
      $this->criarConta();
      
      $criacaoDadosBanc = \Iugu_Marketplace::UpdateBankData();
      
      if ( !$this->lastResponse->success ) {
          throw new UserException("Erro ao inserir os dados bancários");
      }
      
      
    }
    
    
    protected function prepareDebitCard($data){}
    
    
}

?>
