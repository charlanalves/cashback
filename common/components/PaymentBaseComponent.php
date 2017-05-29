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
           $retorno = $this->callMethodDynamically($metodo, $params);
           
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
    
    public function callMethodDynamically($action, $data) 
    {
        $methodExists = method_exists($this, $action);
        
        if ( $methodExists == false ) {
            throw new \Exception("O método $action não existe");
        }
        
        call_user_func_array([$this, $action], [$data]);
    }
    
    public function log(){ }
    
}

?>
