<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\User;
use common\models\LoginForm;
use common\models\CB03CONTABANC;
use common\models\CB04EMPRESA;
use common\models\CB02CLIENTE;
use common\models\CB06VARIACAO;
use common\models\CB10CATEGORIA;
use common\models\VIEWSEARCH;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB16PEDIDO;
use common\models\VIEWEXTRATOCLIENTE;

/**
 * API Iugu Controller
 */
class ApiIuguController extends GlobalBaseController {

    public $url;
    public $urlController;
    
    public function __construct($id, $module, $config = []) {
       
        header('Access-Control-Allow-Origin: *'); 
        
        require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));
        
        \Iugu::setApiKey("5F0CBE968FA94098816EF27F6FA77C36");
        
        parent::__construct($id, $module, $config);
    }
    
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function log(){
        // TODO Implementar um componente de log que salva os erros no banco com o nome da funcao tipo do erro e etc
    }
    
    private function criarSalvarConta($prefix, $tabela, $idDonoConta) 
    {
        $atributos = [];
        $conta = \Iugu_Marketplace::createAccount();      
        
        if (isset($conta->errors)) {
          throw new Exception("Erro ao criar conta");
        }
        
        $atributos[$prefix . 'DADOS_API_TOKEN'] = json_encode($conta);
        
        $pessoa = $tabela::findOne($idDonoConta);
        $pessoa->setAttributes($atributos);
        $pessoa->save();
        
        return $conta;
    }
    
    private function criarSalvarContaCliente($idDonoConta) 
    {
      return $this->criarSalvarConta('CB02_','CB02CLIENTE', $idDonoConta);
    }
    
    private function criarSalvarContaEmpresa($idDonoConta) 
    {
      $conta = $this->criarSalvarConta('CB04_','CB04EMPRESA', $idDonoConta);
      
      $criacaoDadosBanc = \Iugu_Marketplace::UpdateBankData();
      
      if ( !$criacaoDadosBanc->success ) {
          throw new Exception("Erro ao inserir os dados bancários");
      }
      
      return $conta;
    }
    
    public function actionExecute($metodo, $params)
    {
        if (is_null($params)) {
            $params = Yii::$app->request->post();
        }

        $message = '';
        $status = true;

        try {

          $retorno = $this->callMethodDynamically($metodo, $params, true);
          
        } catch (\Exception $e) {
            $this->log();

            $message = $e->getMessage();
            $status = false;
        }

        exit(json_encode(['message' => $message, 'status' => $status, 'retorno' => $retorno]));
    }
    
    
    public function callMethodDynamically($action, $data, $returnThowException = true) 
    {
        $methodExists = method_exists($this, $action);
        
        if ( $methodExists == false ) {
            throw new \Exception("O método $action não existe");
        }
        
        call_user_func_array([$this, $action], [$data]);
    }
    
}
