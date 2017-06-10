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
use common\models\VIEWEXTRATOCLIENTE;

/**
 * API Empresa controller
 */
class ApiEmpresaController extends GlobalBaseController {

    public $url;
    public $urlController;
    
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
        header('Access-Control-Allow-Origin: *'); 
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
                // formas de pagamento do checkout
                $CB16PEDIDO['forma_pagamento'] = CB08FORMAPAGAMENTO::find()
                        ->select(['CB08_ID','CB08_NOME'])
                        ->where(['CB08_STATUS' => 1])
                        ->asArray()
                        ->all();
            }
        }
        
        return json_encode($CB16PEDIDO);
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
                
                //$transaction = \Yii::$app->Iugu->transaction = \Yii::$app->db->beginTransaction();
                $transaction = \Yii::$app->Iugu->transaction = \Yii::$app->db->beginTransaction();
                
                try {
                    
                    $data = $post['data'];
                    
                    // dados do pagamento
                    $PERC_PAG = CB09FORMAPAGTOEMPRESA::find()
                            ->select(['CB09_ID', 'CB09_PERC_ADMIN', 'CB09_PERC_ADQ'])
                            ->where(['CB09_ID_FORMA_PAG' => $data['FORMA-PAGAMENTO'], 'CB09_ID_EMPRESA' => $pedido['CB16_EMPRESA_ID']])
                            ->asArray()
                            ->one();
                    
                    
                    // Atualiza dados do pedido
                    $modelPedido = CB16PEDIDO::findOne($post['order']);
                    $modelPedido->setAttributes([
                        'CB16_ID_FORMA_PAG_EMPRESA' => $PERC_PAG['CB09_ID'],
                        'CB16_FORMA_PAG' => CB08FORMAPAGAMENTO::findOne($data['FORMA-PAGAMENTO'])->CB08_NOME,
                        'CB16_PERC_ADMIN' => $PERC_PAG['CB09_PERC_ADMIN'],
                        'CB16_PERC_ADQ' => $PERC_PAG['CB09_PERC_ADQ'],
                        'CB16_STATUS' => CB16PEDIDO::status_pago,
                        'CB16_DT_APROVACAO' => date('Y-m-d H:i:s'),
                        'CB16_TRANS_CRIADAS' => 1
                    ]);
                    $modelPedido->save();
                    

                    // checkout Iugu
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
                    
                    \Yii::$app->Iugu->execute('processTransaction', $param);
                    
                } catch (\Exception $exc) {
                    var_dump('Exception');
                    $transaction->rollBack();
                    return json_encode(['status' => false]);
                    
                }
                
            }
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
        
        return json_encode(['IMG' => $imagens, 'PRODUTO' => $post['product']]);
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
        
        return json_encode(['PROMOCOES' => $promocoes, 'PRODUTO' => $post['product']]);
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
                    $pedido->CB16_VLR_CB_TOTAL = \Yii::$app->u->arredondar(($dadosV['CB06_DINHEIRO_VOLTA'] / $dadosV['CB06_PRECO_PROMOCIONAL']) * 100);
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
