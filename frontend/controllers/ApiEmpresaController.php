<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\User;
use common\models\LoginForm;
use common\models\CB03CONTABANC;
use common\models\CB04EMPRESA;
use common\models\CB05PRODUTO;
use common\models\CB06VARIACAO;
use common\models\CB08FORMAPAGAMENTO;
use common\models\CB09FORMAPAGTOEMPRESA;
use common\models\CB10CATEGORIA;
use common\models\VIEWSEARCH;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB14FOTOPRODUTO;
use common\models\CB16PEDIDO;
use common\models\CB17PRODUTOPEDIDO;
use common\models\CB18VARIACAOPEDIDO;
use common\models\VIEWEXTRATO;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\PAG04TRANSFERENCIAS;

/**
 * API Empresa controller
 */
class ApiEmpresaController extends GlobalBaseController {

    public $url;
    public $urlController;
    public $invoiceId = null;
    
    public function __construct($id, $module, $config = []) {
        $this->url = \Yii::$app->request->hostInfo . '/apiestalecas/frontend/web/';
        $this->urlController = $this->url . 'index.php?r=api-empresa/';
        parent::__construct($id, $module, $config);
		header('Access-Control-Allow-Origin: *'); 
    }
    
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    private function validateUser($data) {
        
        try {
            
            if(is_array($data)) {
                $user = $data['auth_key'];
            } else {
                $user = $data;
            }

            if (empty($user)) {
                throw new \Exception();
            }

            if (!($idUser = User::getIdByAuthKey($user))) {
                throw new \Exception();
            }

            return $idUser;

        } catch (\Exception $exc) {
            return false;
        }

    }
    
    
    /**
     * Id da conta Iugu
     */
    public function actionIuguIdAccount() {
        if($this->validateUser(\Yii::$app->request->post())) {
            return json_encode(SYS01PARAMETROSGLOBAIS::getValor('ID_JS'));
        } else {
            return false;
        }        
    }
    
    public function actionIugu() {
        require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));

        $a = \Iugu::setApiKey("67dfbb35a60a62cb5cee9ca8730737a98");
        $teste = \Iugu_Charge::create(Array(
                    "token" => "E4DE70F8-3574-4A69-A83E-0D507318DA26",
                    "email" => "teste@teste.com",
                    "items" => Array(
                        Array(
                            "description" => "Item Um",
                            "quantity" => "1",
                            "price_cents" => "1000"
                        )
                    ),
                    "payer" => Array(
                        "name" => "Item Um",
                        "phone_prefix" => "1",
                        "phone" => "1000",
                        "email" => "teste@teste.com",
                        "address" => Array(
                            "street" => "Rua Tal",
                            "number" => "700",
                            "city" => "São Paulo",
                            "state" => "SP",
                            "country" => "Brasil",
                            "zip_code" => "12122-00"
                        )
                    )
        ));
        var_dump($teste);
    }

    /**
     * getSaldoAtual
     * @param string/integer $user ID ou AUTHKEY do usuario
     * @return string saldo atual do usuario
     */
    private function getSaldoAtual($user) {
        return VIEWEXTRATO::saldoAtualByCliente(( is_numeric($user) ? $user : User::getIdByAuthKey($user))) ? : '0,00';
    }

    
    /**
     * getSaldoPendente
     * @param string/integer $user ID ou AUTHKEY do usuario
     * @return string saldo pendente do usuario
     */
    private function getSaldoPendente($user) {
        return VIEWEXTRATO::saldoPendenteByCliente(( is_numeric($user) ? $user : User::getIdByAuthKey($user))) ? : '0,00';
    }
    
    
    /**
     * getSaldo
     * @param string/integer $user ID ou AUTHKEY do usuario
     * @return string saldo atual liberado e pendente do usuario ['SALDO_LIBERADO','SALDO_PENDENTE']
     */
    private function getSaldo($user) {
        return VIEWEXTRATO::saldoAtualePendenteByCliente(( is_numeric($user) ? $user : User::getIdByAuthKey($user))) ? : ['SALDO_LIBERADO' => '0,00','SALDO_PENDENTE' => '0,00'];
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
        header('Access-Control-Allow-Origin: *'); 
        $model = new LoginForm();
        $model->setAttributes(\Yii::$app->request->post());
        $model->loginCpfCnpj(); 
        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }
    
    
    /**
     * Esqueceu a senha
     */
    public function actionNovaSenha() {
        header('Access-Control-Allow-Origin: *');
        $model = new LoginForm();
        $model->setAttributes(\Yii::$app->request->post());
        $user = $model->getUserByCpfCnpj();
        
        $email = '';
        if(!empty($user['email'])) {
            $email = $user['email'];

            /* @var $user User */
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $email,
            ]);

            if (!$user) {
                return false;
            }

            // nova senha 
            $new_password = strtoupper(substr(uniqid(),-5));
            $new_password_hash = \Yii::$app->security->generatePasswordHash($new_password);
            $user->setAttribute('password_hash', $new_password_hash);
            $user->setAttribute('password_reset_token', $new_password_hash);
            if (!$user->save()) {
                return false;
            }
            
            \Yii::$app->sendMail->enviarEmailNovaSenha($email, $new_password);
        }
        
        return json_encode(['status' => $user == null ? null : !!$user, 'email' => $email]);
    }
    
    
    /**
     * Reenviar email: validacao de email
     */
    public function actionReenviarEmailValidacao() 
    {
       $post = \Yii::$app->request->post();
       $user = \common\models\User::findOne(['id' => $post['id']]);
       \Yii::$app->sendMail->enviarEmailCadastro($user->email, $user->auth_key);
    }
    
    /**
     * Validacao de email
     */
    public function actionValidMail() {
        $cod = \Yii::$app->request->get('c');
        if($cod) {
            $user = User::findOne(['auth_key' => $cod]);
            if($user) {
                $user->setAttribute('email_valid', 1);
                return $user->save();
            }
        }
        return false;
    }
    
    public function actionValidarUsuario()
    {        
        $authKey = \Yii::$app->request->get('authKey');        
        if (!empty($authKey)) {
            $user = User::findOne(['auth_key' => $authKey]);            
            $user->email_valid = 1;
            if ($user->save(false)){
                echo '<h1>Conta validada com sucesso! Abra o aplicativo e comece a ganhar dinheiro de volta.</h1>';
            }
        }
    }
    
    /**
     * Verifica se o email foi validado
     */
    public function actionVerificaEmail() {
        return ($user = User::findByCpfCnpj(\Yii::$app->request->post('cpf_cnpj'))) ? (int) $user->email_valid : 0;
    }
    
    
    /**
     * Alterar o email do usuário
     */
    public function actionAlterarEmailUsuario() {
        $post = \Yii::$app->request->post();
        $user = User::findOne($post['param']['id']);
        if ($user) {
            $user->email = $post['new_email'];
            if(!$user->validate()){
                return $user->errors['email'][0];
            } else {
                \Yii::$app->sendMail->enviarEmailCadastro($post['new_email'], $user->auth_key);
                return $user->save(false);
            }
        }
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
      \Yii::$app->Iugu->execute('createSaveClienteAccount', \Yii::$app->request->post());
       
      
    }
    
	public function actionCmaster()
    {
       \Yii::$app->Iugu->execute('createAccount','');
	      
    }
    
    /**
     * Promocoes
     */
    public function actionPromocao() {
        $filter = \Yii::$app->request->post();
//        $saldo = $this->getSaldo(\Yii::$app->request->post('user_auth_key'));
//        $saldoAtual = $saldo['SALDO_LIBERADO'];
//        $saldoPendente = $saldo['SALDO_PENDENTE'];
        $saldoAtual = $this->getSaldoAtual(\Yii::$app->request->post('user_auth_key'));
        $saldoPendente = 0;
        $CB06VARIACAO = CB06VARIACAO::getPromocao($this->url, $filter);
        return json_encode(['saldoLiberado' =>  $saldoAtual, 'saldoPendente' => $saldoPendente, 'estabelecimentos' => $CB06VARIACAO]);
    }

    
    /**
     * Pesquisa
     */
    public function actionSearch() {
        if ( ($param = \Yii::$app->request->post('param')) ) {
            return json_encode(VIEWSEARCH::getBuscaProduto($param));
        } else {
            return "{}";
        }
    }
    
    
    /**
     * Lista promocoes por categoria
     */
    public function actionPromotionsByCategory() {
        if ( ($param = \Yii::$app->request->post('param')) ) {
            return json_encode(['category' => CB10CATEGORIA::findOne($param)->CB10_NOME, 'result' => VIEWSEARCH::getPromotionsByCategory($param)]);
        } else {
            return "{}";
        }
    }
    
    
    /**
     * Categorias do filtro
     */
    public function actionFilterCategory() {
        $CB10CATEGORIA = CB10CATEGORIA::find()->where(['CB10_STATUS' => 1])->asArray()->all();
        return json_encode($CB10CATEGORIA);
    }
    
    
    /**
     * Categorias
     */
    public function actionCategory() {
        $CB10CATEGORIA = CB10CATEGORIA::getMaxCachback();
        return json_encode(['categoria' => $CB10CATEGORIA, 'saldo' => $this->getSaldoAtual(\Yii::$app->request->post('user_auth_key'))]);
    }
    
    
    /**
     * Convidar amigo
     */
    public function actionInviteFriend() {
        $SYS01PARAMETROSGLOBAIS = "";
        if ( ($user = \Yii::$app->request->post('user_auth_key')) ) {
            $SYS01PARAMETROSGLOBAIS = SYS01PARAMETROSGLOBAIS::getValor('1') . $user;
        }
        return json_encode(['texto' => $SYS01PARAMETROSGLOBAIS, 'regras' => SYS01PARAMETROSGLOBAIS::getValor('CON_REG')]);
    }
    
    
    /**
     * Sacar
     */
    public function actionCashOut() {
        
        $return = '';        
        $saque_realizado = false;
        $formData = \Yii::$app->request->post();
        
        if (($user = $formData['user_auth_key'])) {
            
            unset($formData['user_auth_key']);
            if (($idUser = User::getIdByAuthKey($user))) {

                $saldoAtual = $this->getSaldoAtual($idUser);
                $saqueMax = (float) $saldoAtual;
                $saqueMin = (float) SYS01PARAMETROSGLOBAIS::getValor('2');
                $regras = SYS01PARAMETROSGLOBAIS::getValor('SQ_REGR');

                $contaBancariaCliente = CB03CONTABANC::findOne(['CB03_USER_ID' => $idUser]);
                $dadosSaque = ($contaBancariaCliente) ? : new CB03CONTABANC();
                $dadosSaque->setAttribute('CB03_VALOR', '');
                
                $dadosSaque->scenario = 'saque';
                
                if (!$formData) {
                    $dadosSaque->setAttribute('CB03_USER_ID', $idUser);
                    $dadosSaque->setAttribute('CB03_SAQUE_MIN', $saqueMin);
                    $dadosSaque->setAttribute('CB03_SAQUE_MAX', $saqueMax);

                } else {
                    
                    // formata dados da conta e agencia
                    $dMask = \Yii::$app->u->setMaskBancaria($formData['CB03_COD_BANCO'], $formData['CB03_AGENCIA'], $formData['CB03_NUM_CONTA'], $formData['CB03_TP_CONTA']);
                    $formData['CB03_AGENCIA'] = $dMask['A'];
                    $formData['CB03_NUM_CONTA'] = $dMask['C'];
                    
                    $dadosSaque->setAttributes($formData);
                    $dadosSaque->setAttribute('CB03_USER_ID', $idUser);
                    $dadosSaque->setAttribute('CB03_SAQUE_MIN', $saqueMin);
                    $dadosSaque->setAttribute('CB03_SAQUE_MAX', $saqueMax);
                            
                    if ($dadosSaque->validate()) {
                    
                        $transaction = \Yii::$app->db->beginTransaction();

                        try {

                            $dadosSaque->save(false);

                            $PAG04TRANSFERENCIAS = new PAG04TRANSFERENCIAS();
                            $PAG04TRANSFERENCIAS->setAttributes([
                                'PAG04_ID_USER_CONTA_ORIGEM' => $idUser,
                                'PAG04_DT_PREV' => date('Y-m-d', strtotime("+" . SYS01PARAMETROSGLOBAIS::getValor('PO_SQ') ." days", strtotime(date('Y-m-d')))),
                                'PAG04_VLR' => $dadosSaque->CB03_VALOR,
                                'PAG04_TIPO' => 'V2B',
                            ]);
                            $PAG04TRANSFERENCIAS->save();

                            $saque_realizado = true;
                            $transaction->commit();

                        } catch (\Exception $exc) {
                            $transaction->rollBack();
                        }

                    } else {
                        // formata valor moeda REAL
                        $dadosSaque->setAttribute('CB03_VALOR', (string) \Yii::$app->u->moedaReal($dadosSaque->attributes['CB03_VALOR']));
                    }
                }
            }
        }
        
        return json_encode([
            'saque_realizado' => $saque_realizado,
            'utl_action' => $this->urlController . 'cash-out',
            'bancos' => \Yii::$app->u->getBancos(),
            'tp_conta' => \Yii::$app->u->getTipoContaBancaria(),
            'conta_bancaria' => $dadosSaque->getAttributes(),
            'error' => ($dadosSaque->getErrors() ? : false),
            'regras' => $regras,
        ]);
    }
    
    
    /**
     * Mensagem de saque
     */
    public function actionCashOutMessage() {
        $saldoAtual = $prazoSaque = '';
        $post = \Yii::$app->request->post();
        if (($user = $post['user_auth_key'])) {
            if (($idUser = User::getIdByAuthKey($user))) {
                $saldoAtual = $this->getSaldoAtual($idUser);
                $prazoSaque = SYS01PARAMETROSGLOBAIS::getValor('PB_SQ');
            }
        }
        return json_encode(['saldoAtual' => $saldoAtual, 'prazo' => $prazoSaque]);
    }
    
    
    /**
     * Politica de privacidade + Termos de uso
     */
    public function actionPoliticaPrivacidadeTermosUso() {
        return json_encode(['PP' => SYS01PARAMETROSGLOBAIS::getValor('TXT_PP'), 'TU' => SYS01PARAMETROSGLOBAIS::getValor('TXT_TU')]);
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
     * Extrato
     */
    public function actionExtract() {
        $saldoAtual = '';
        $post = \Yii::$app->request->post();
        if (($user = $post['user_auth_key'])) {
            if (($idUser = User::getIdByAuthKey($user))) {
                $saldoAtual = $this->getSaldoAtual($idUser);
            }
        }
        
        function ultimosMeses ($qtdMeses = 12) {
            $voltaAno = 0; 
            $objMeses = [];
            $meses = array(
                1 => 'Janeiro',
                'Fevereiro',
                'Março',
                'Abril',
                'Maio',
                'Junho',
                'Julho',
                'Agosto',
                'Setembro',
                'Outubro',
                'Novembro',
                'Dezembro'
            );
            
            for($m=1;$m<=$qtdMeses;$m++){

                $numMes = date('n')-$m;
                $ano = date('Y');

                if(!empty($voltaAno)) {
                    $numMes = $numMes+($voltaAno*12);
                }
                
                if($numMes==0){
                    $numMes = 12; $voltaAno++;
                }
                
                if(!empty($voltaAno)) { 
                    $ano = $ano - $voltaAno;  
                }
                
                $objMeses[$ano . '-' . $numMes] = $meses[$numMes] . "/" . $ano;

            }
            return $objMeses;
        }
                
        return json_encode(['saldo' => $saldoAtual, 'ultimosMeses' => ultimosMeses()]);
    }
    
    
    /**
     * Extrato - LISTA
     */
    public function actionExtractList() {
        $extrato = '';
        $post = \Yii::$app->request->post();
        if (($idUser = User::getIdByAuthKey($post['user_auth_key'])) && ($periodo = $post['periodo'])) {
            $extrato = VIEWEXTRATO::extractUser($idUser,$periodo);         
        }
        return json_encode($extrato);
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
            $user->setAttribute('password_reset_token', NULL);
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
                // formas de pagamento do checkout
                $CB16PEDIDO['forma_pagamento'] = CB04EMPRESA::find()
                        ->select(['CB08_ID','CB08_NOME'])
                        ->join('JOIN','CB09_FORMA_PAGTO_EMPRESA','CB09_FORMA_PAGTO_EMPRESA.CB09_ID_EMPRESA = CB04_EMPRESA.CB04_ID')
                        ->join('JOIN','CB08_FORMA_PAGAMENTO','CB08_FORMA_PAGAMENTO.CB08_ID = CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG')
                        ->where(['CB08_STATUS' => 1])
                        ->andWhere(['CB04_EMPRESA.CB04_ID' => $CB16PEDIDO['CB16_EMPRESA_ID']])
                        ->orderBy('CB08_ID')
                        ->asArray()
                        ->all();
                
                // saldo estaleca sempre tem que ser o primeiro 
                // o saldo deve ser maior que o valor da compra
                $saldoAtual = $this->getSaldoAtual($post['user_auth_key']);
                if($CB16PEDIDO['CB16_VALOR'] <= $saldoAtual) {
                    $CB16PEDIDO['forma_pagamento'][0]['CB08_NOME'] = $CB16PEDIDO['forma_pagamento'][0]['CB08_NOME'] .' (R$ '. $saldoAtual . ')';
                } else {
                    unset($CB16PEDIDO['forma_pagamento'][0]);
                }
                
            }   
        }
        
        return json_encode($CB16PEDIDO);
    }

    private function getPercPag($data, $pedido)
    {	
       return CB09FORMAPAGTOEMPRESA::find()
                 ->select(['CB09_ID', 'CB09_PERC_ADMIN', 'CB09_PERC_ADQ'])
                 ->where(['CB09_ID_FORMA_PAG' => $data['FORMA-PAGAMENTO'], 'CB09_ID_EMPRESA' => $pedido['CB16_EMPRESA_ID']])
                 ->asArray()
                 ->one();                    
    }

    private function atualizaPedidoPago($post, $PERC_PAG, $data)
    {
       $modelPedido = CB16PEDIDO::findOne($post['order']);
       $modelPedido->scenario = CB16PEDIDO::SCENARIO_ATUALIZA_PEDIDO_PAGO;
       
       $modelPedido->CB16_ID_FORMA_PAG_EMPRESA = $PERC_PAG['CB09_ID'];
       $modelPedido->CB16_FORMA_PAG = CB08FORMAPAGAMENTO::findOne($data['FORMA-PAGAMENTO'])->CB08_NOME;
       $modelPedido->CB16_PERC_ADMIN  = $PERC_PAG['CB09_PERC_ADMIN'];
       $modelPedido->CB16_PERC_ADQ = $PERC_PAG['CB09_PERC_ADQ'];
       $modelPedido->CB16_STATUS = CB16PEDIDO::status_pago;
       $modelPedido->CB16_DT_APROVACAO = date('Y-m-d H:i:s');
       $modelPedido->CB16_TRANS_CRIADAS = 0;
                           
       $modelPedido->save();
    }
    
    
    private function preparaProcessaTransacao($pedido, $data)
    {
         $param = [
                  	"token" => $data['token'],
                    "email" => $data['EMAIL'],
                    "items" => Array(
                            Array(
                                "description" => $pedido['CB17_NOME_PRODUTO'],
                                "quantity" => $pedido['CB17_QTD'],
                                "price_cents" => \Yii::$app->u->arredondar($pedido['CB16_VALOR'] * 100)
                            )
                        ),
                   "payer" => Array(
                         "name" => $data['NOME'],
                         "phone_prefix" => "",
                         "phone" => $data['TELEFONE'],
                         "email" => $data['EMAIL'],
                         "address" => Array(
                             "street" => "",
                             "number" => "",
                             "city" => "",
                             "state" => "",
                             "country" => "",
                             "zip_code" => ""
                            )
                        )
                    ];
                   
        $retorno = \Yii::$app->Iugu->exec('processTransaction', $param);
       
        if (isset($retorno['invoice_id'])) {
             $this->invoiceId = $retorno['invoice_id'];
        }            
    }
                    
    /**
     * Checkout - Comprar
     */
    public function actionCheckoutPurchase() {
        $post = \Yii::$app->request->post();
        
        $status = false;
        $message = '';
        
        // verifica se o pedido é do usuario logado
        if(($pedido = CB16PEDIDO::getPedidoByAuthKey($post['auth_key'], "", $post['order']))) {
            $pedido = $pedido[0];
            // verifica status do pedido
            if ($pedido['CB16_STATUS'] == CB16PEDIDO::status_aguardando_pagamento) {
                
                $transaction = \Yii::$app->db->beginTransaction();
                
                try {
                    $data = $post['data'];
                    
                    // Dados do pagamento
                    $PERC_PAG = $this->getPercPag($data, $pedido);
                	
                    // Atualiza dados do pedido
                    $this->atualizaPedidoPago($post, $PERC_PAG, $data);
                    
                    // Transferencia - pagar com saldo
                    if($data['FORMA-PAGAMENTO'] == 1) {
                        \Yii::$app->Iugu->criaTransferenciaPagSaldo($pedido['CB17_PEDIDO_ID']);
                        $this->invoiceId = null;
                        
                        
                    } else {
                        // Prepara e processa Transacao iugu
                        $this->preparaProcessaTransacao($pedido, $data);
                        
                    }
                    
                    $transaction->commit();
                    \Yii::$app->Iugu->execute('criaTransferencias', ['pedido' => '']);
                    $status = true;
                    $retorno = '';
                    
                } catch (\Exception $exc) {
                    $transaction->rollBack();
                    $retorno = $exc->getMessage();
                }
                if (!is_null($this->invoiceId)) {
                	$this->atualizaCodTransacaoPedido($post['order'], $this->invoiceId);
                }
                
                exit(json_encode(['status' => $status, 'retorno' => $retorno]));
            }
        }
    }
    
   private function atualizaCodTransacaoPedido($idPedido, $codTransacao)
   { 
       try {    	  
             // Atualiza codigo da transação se acontecer um erro continua o fluxo
          	 $modelPedido = CB16PEDIDO::findOne($idPedido);
             $modelPedido->CB16_COD_TRANSACAO = $codTransacao;     
             $modelPedido->save();
              
        }catch (\Exception $exc) {                    
             // Se acontecer um erro continua o fluxo
        }
                
   }
    /**
     * Estabelecimento e seus produto
     */
    public function actionCompany() {
        $post = \Yii::$app->request->post(); 
        $produtoAtual = null;

        // produtos da empresa
        $produto = CB05PRODUTO::find()
            ->where(['CB05_EMPRESA_ID' => $post['company'], 'CB05_ATIVO' => 1])
            ->orderBy('CB05_NOME_CURTO')
            ->asArray()
            ->all();

        // produto que aparece aberto na tela - verifica post
        if ($produto) {
            if ($post['product']) {
                foreach ($produto as $k => $p) {
                    if ($p['CB05_ID'] == $post['product']) {
                        $produtoAtual = $produto[$k];
                        $produto[$k]['active'] = 'active';
                        break;
                    }
                }
            }
            // se nao foi informado - ativa o primeiro produto
            if (is_null($produtoAtual)) {
                $produtoAtual = $produto[0];
                $produto[0]['active'] = 'active';
            }
        }

        return json_encode(['produtos' => $produto, 'produtoAtual' => $produtoAtual]);
    }
    
    
    /**
     * Produto do estabelecimento
     */
    public function actionCompanyProduct() {
        $post = \Yii::$app->request->post();
        
        // imagens do produto
        $imagens = CB14FOTOPRODUTO::find()
            ->where(['CB14_PRODUTO_ID' => $post['product']])
            ->orderBy('CB14_CAPA DESC')
            ->asArray()
            ->all();
        
        // produtos da empresa
        $PRODUTO_DATA = CB05PRODUTO::getProduto($post['product']);
        
        return json_encode(['IMG' => $imagens, 'PRODUTO' => $post['product'], 'PRODUTO_DATA' => $PRODUTO_DATA]);
    }
    
    
    /**
     * Promocoes/variacoes do produto
     */
    public function actionCompanyPromotions() {
        $post = \Yii::$app->request->post();

        // promocoes/variacoes do produto
        $promocoes = CB06VARIACAO::find()
            ->where(['CB06_PRODUTO_ID' => $post['product']])
            ->orderBy('CB06_DESCRICAO')
            ->asArray()
            ->all();
        
        return json_encode(['PROMOCOES' => $promocoes, 'PRODUTO' => $post['product'], 'PROMOCAO_SELECIONADA' => (!empty($post['promotion'])) ? $post['promotion']: false]);
    }
    
    
    /**
     * Regras/informacoes do produto
     */
    public function actionCompanyRules() {
        $post = \Yii::$app->request->post();
        
        $CB05_IMPORTANTE = CB05PRODUTO::find()
                ->select(['CB05_IMPORTANTE'])
                ->where(['CB05_ID' => $post['product'], 'CB05_ATIVO' => 1])
                ->orderBy('CB05_NOME_CURTO')
                ->asArray()
                ->one();
        
        return json_encode($CB05_IMPORTANTE);
    }
    
    
    /**
     * Local do estabelecimento/produto
     */
    public function actionCompanyLocal() {
        $post = \Yii::$app->request->post();
        
        $CB04EMPRESA = CB04EMPRESA::find()
                ->where(['CB04_ID' => $post['company']])
                ->asArray()
                ->one();
        
        return json_encode($CB04EMPRESA);
    }
    
    
    /**
     * Mensagem apos realizar a compra
     */
    public function actionPurchaseMessage() {        
        return json_encode(['message' => SYS01PARAMETROSGLOBAIS::getValor('MSG_BUY')]);
    }
    
    
    public function actionCompanyBuyProduct() {
        $post = \Yii::$app->request->post();
        
        if (!($user = $post['auth_key'])) {
            return false;
        }
        
        unset($post['auth_key']);
        if (!($idUser = User::getIdByAuthKey($user))) {
            return false;
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {


            if(($produto = $post['produto']) && ($variacao = $post['promocao'])) {

                $dadosP = CB05PRODUTO::findOne(['CB05_ID' => $produto]);
                $dadosV = CB06VARIACAO::findOne(['CB06_ID' => $variacao]);
                
                if($dadosP && $dadosV) {

                    $dadosP = $dadosP->attributes;
                    $dadosV = $dadosV->attributes;

                    // salva pedido
                    $pedido = new CB16PEDIDO();
                    $pedido->CB16_EMPRESA_ID = $dadosP['CB05_EMPRESA_ID'];                    
                    $pedido->CB16_USER_ID = $idUser;
                    $pedido->CB16_VALOR = $dadosV['CB06_PRECO_PROMOCIONAL'];
                    $pedido->CB16_VLR_CB_TOTAL = \Yii::$app->u->arredondar($dadosV['CB06_PRECO_PROMOCIONAL'] * ($dadosV['CB06_DINHEIRO_VOLTA'] / 100));
                    $pedido->save();
                    $idPedido = $pedido->CB16_ID;

                    // salva itens pedido
                    $produtoPedido = new CB17PRODUTOPEDIDO();
                    $produtoPedido->CB17_PEDIDO_ID = $idPedido;
                    $produtoPedido->CB17_PRODUTO_ID = $dadosP['CB05_ID'];
                    $produtoPedido->CB17_NOME_PRODUTO =  $dadosP['CB05_TITULO'] . ' - ' . $dadosV['CB06_DESCRICAO'];
                    $produtoPedido->CB17_VLR_UNID = $dadosV['CB06_PRECO'];
                    $produtoPedido->CB17_VARIACAO_ID = $dadosV['CB06_ID'];
                    $produtoPedido->CB17_QTD = 1;
                    $produtoPedido->save();

                    $transaction->commit();
                    return $idPedido;
                }
            }
            return false;

        } catch (\Exception $exc) {
            $transaction->rollBack();
            //var_dump($exc->getMessage());
            return false;
        }
    }
    
}
