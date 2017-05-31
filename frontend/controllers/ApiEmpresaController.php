<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\User;
use common\models\LoginForm;
use common\models\CB03CONTABANC;
use common\models\CB04EMPRESA;
use common\models\CB06VARIACAO;
use common\models\CB10CATEGORIA;
use common\models\VIEWSEARCH;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB16PEDIDO;
use common\models\VIEWEXTRATOCLIENTE;

/**
 * API Empresa controller
 */
class ApiEmpresaController extends GlobalBaseController {

    public $url;
    public $urlController;
    
    public function __construct($id, $module, $config = []) {
        $this->url = \Yii::$app->request->hostInfo . '/cashback/frontend/web/';
        $this->urlController = $this->url . 'index.php?r=api-empresa/';
        parent::__construct($id, $module, $config);
    }
    
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    
    /**
     * getSaldoAtual
     * @param string/integer $user ID ou AUTHKEY do usuario
     * @return string saldo atual do usuario
     */
    private function getSaldoAtual($user) {
        return VIEWEXTRATOCLIENTE::saldoAtualByCliente(( is_numeric($user) ? $user : User::getIdByAuthKey($user))) ? : '0,00';
    }
    
    
    /**
     * Index.
     */
    public function actionIndex() {
        //return $this->redirect(\yii\helpers\Url::to('index.php?r=api-empresa/login'));
    }

    
    /**
     * Login
     */
    public function actionLogin() {
        $model = new LoginForm();
        $model->setAttributes(\Yii::$app->request->post());
        $model->loginCpfCnpj(); 
        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }

    
    /**
     * Login Active
     */
    public function actionLoginActive() {
        $model = \common\models\User::findOne(['auth_key' => \Yii::$app->request->post('auth_key')]);
        return json_encode(($model ? $model->attributes : ['error' => [[['Faça o login']]]]));
    }
    
    
    /**
     * Login Create.
     */
    public function actionLoginCreate()
    {
        $model = new \frontend\models\SignupForm();
        $model->setAttributes(\Yii::$app->request->post());
        $model->signup();
        return json_encode(($model->errors ? ['error' => $model->errors] : $model->attributes));
    }
    
    
    /**
     * Promocoes
     */
    public function actionPromocao() {
        $filter = \Yii::$app->request->post();
        $saldoAtual = $this->getSaldoAtual(\Yii::$app->request->post('user_auth_key'));
        $CB06VARIACAO = CB06VARIACAO::getPromocao($this->url, $filter);
        return json_encode(['saldoAtual' =>  $saldoAtual, 'estabelecimentos' => $CB06VARIACAO]);
    }

    
    /**
     * Pesquisa
     */
    public function actionSearch() {
        $retorno = "{}";
        if ( ($param = \Yii::$app->request->post('param')) ) {
            $VIEWSEARCH = VIEWSEARCH::find()->where(['like', 'BUSCA_TEXTO', $param])->asArray()->all();
            return json_encode($VIEWSEARCH);
        }
        return $retorno;
    }
    
    
    /**
     * Categorias do filtro
     */
    public function actionFilterCategory() {
        $CB10CATEGORIA = CB10CATEGORIA::find()->asArray()->all();
        return json_encode($CB10CATEGORIA);
    }
    
    
    /**
     * Convidar amigo
     */
    public function actionInviteFriend() {
        $SYS01PARAMETROSGLOBAIS = "";
        if ( ($user = \Yii::$app->request->post('user_auth_key')) ) {
            $SYS01PARAMETROSGLOBAIS = SYS01PARAMETROSGLOBAIS::getValor('1') . $user;
        }
        return json_encode($SYS01PARAMETROSGLOBAIS);
    }
    
    
    /**
     * Sacar
     */
    public function actionCashOut() {
        
        $return = '';
        $formData = \Yii::$app->request->post();
        
        if (($user = $formData['user_auth_key'])) {
            
            unset($formData['user_auth_key']);
            if (($idUser = User::getIdByAuthKey($user))) {

                $saldoAtual = $this->getSaldoAtual($idUser);
                $saqueMax = (float) $saldoAtual;
                $saqueMin = (float) SYS01PARAMETROSGLOBAIS::getValor('2');

                $contaBancariaCliente = CB03CONTABANC::findOne(['CB03_CLIENTE_ID' => $idUser]);
                $dadosSaque = ($contaBancariaCliente) ? : new CB03CONTABANC();
                $dadosSaque->setAttribute('CB03_VALOR', '');

                $dadosSaque->scenario = CB03CONTABANC::SCENARIO_SAQUE;

                if ($formData) {
                    $dadosSaque->setAttributes($formData);
                    $dadosSaque->setAttribute('CB03_CLIENTE_ID', $idUser);
                    $dadosSaque->setAttribute('CB03_SAQUE_MIN', $saqueMin);
                    $dadosSaque->setAttribute('CB03_SAQUE_MAX', $saqueMax);

                    if ($dadosSaque->validate()) {
                        $dadosSaque->save(false);

                    } else {
                        // formata valor moeda REAL
                        $dadosSaque->setAttribute('CB03_VALOR', (string) \Yii::$app->u->moedaReal($dadosSaque->attributes['CB03_VALOR']));
                    }
                }
            }
        }
        
        return json_encode([
            'utl_action' => $this->urlController . 'cash-out',
            'bancos' => \Yii::$app->u->getBancos(),
            'tp_conta' => \Yii::$app->u->getTipoContaBancaria(),
            'conta_bancaria' => $dadosSaque->getAttributes(),
            'error' => ($dadosSaque->getErrors() ? : false)
        ]);
    }
    
    
    /**
     * Compras realizadas
     */
    public function actionShopping() {
        $modelCB16PEDIDO = new CB16PEDIDO();
        $CB16PEDIDO = (($user = \Yii::$app->request->post('user_auth_key'))) ? $modelCB16PEDIDO::getPedidoByAuthKey($user) : false;
        return json_encode($CB16PEDIDO);
    }
    
    
    /**
     * Estabelecimentos
     */
    public function actionEstablishment() {
        $CB04EMPRESA = CB04EMPRESA::getEmpresas(\Yii::$app->request->post());
        return json_encode($CB04EMPRESA);
    }
    
    
    /**
     * Perfil
     */
    public function actionProfile() {
        return '{}';
    }
    
    
    /**
     * Alterar senha 
     */
    public function actionChangePassword() {
        $post = \Yii::$app->request->post();
        $current_password = $post['current-password'];
        $new_password = $post['new-password'];
        $auth_key = $post['auth_key'];
        $retorno = [];
        
        // valida senha
        if (\Yii::$app->security->validatePassword($current_password, User::getHashPasswordByAuthKey($auth_key))) {
            $new_password_hash = \Yii::$app->security->generatePasswordHash($new_password);
            $user = User::findOne(['auth_key' => $auth_key]);
            $user->setAttribute('password_hash', $new_password_hash);
            if ($user->save()) {
                $retorno = ['message' => 'A senha foi alterada com sucesso!'];
            } else {
                $retorno = ['error' => [[['A senha não foi alterada, tente novamente!']]]];
            }
            
        } else {
            $retorno = ['error' => [[['A senha atual esta incorreta!']]]];
            
        }
        
        return json_encode($retorno);
    }
    
    
    /**
     * Checkout
     */
    public function actionCheckout() {
        $post = \Yii::$app->request->post();
        $CB16PEDIDO = false;
        
        // verifica se o pedido é do usuario logado
        if(($pedido = CB16PEDIDO::getPedidoByAuthKey($post['user_auth_key'], "", $post['order']))) {
            $pedido = $pedido[0];
            // verifica status do pedido
            if ($pedido['CB16_STATUS'] == CB16PEDIDO::status_aguardando_pagamento) {
                $CB16PEDIDO = $pedido;
            }
        }
        
        return json_encode($CB16PEDIDO);
    }

}
