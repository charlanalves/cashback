<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\User;
use common\models\LoginForm;
use common\models\CB03CONTABANC;
use common\models\CB04EMPRESA;
use common\models\CB05PRODUTO;
use common\models\CB06VARIACAO;
use common\models\CB07CASHBACK;
use common\models\CB08FORMAPAGAMENTO;
use common\models\CB09FORMAPAGTOEMPRESA;
use common\models\CB10CATEGORIA;
use common\models\VIEWSEARCH;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB14FOTOPRODUTO;
use common\models\CB16PEDIDO;
use common\models\CB17PRODUTOPEDIDO;
use common\models\CB18VARIACAOPEDIDO;
use common\models\CB19AVALIACAO;
use common\models\CB12ITEMCATEGEMPRESA;
use common\models\CB21RESPOSTAAVALIACAO;
use common\models\CB22COMENTARIOAVALIACAO;
use common\models\VIEWEXTRATO;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\PAG04TRANSFERENCIAS;

/**
 * API Comissao controller
 */
class ApiComissaoController extends GlobalBaseController
{

    public $url;
    public $urlController;
    public $invoiceId = null;

    public function __construct($id, $module, $config = [])
    {
        $this->url = \Yii::$app->request->hostInfo . '/apiestalecas/frontend/web/';
        $this->urlController = $this->url . 'index.php?r=api-comissao/';
        parent::__construct($id, $module, $config);
        header('Access-Control-Allow-Origin: *');
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function validateUser($data)
    {

        try {

            if (is_array($data)) {
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
    public function actionIuguIdAccount()
    {
        if ($this->validateUser(\Yii::$app->request->post())) {
            return json_encode(SYS01PARAMETROSGLOBAIS::getValor('ID_JS'));
        } else {
            return false;
        }
    }

    /**
     * getSaldoAtual
     * @param string/integer $user ID ou AUTHKEY do usuario
     * @return string saldo atual do usuario
     */
    private function getSaldoAtual($user)
    {
        return VIEWEXTRATO::saldoAtualComissao(( is_numeric($user) ? $user : User::getIdByAuthKey($user))) ? : '0,00';
    }

    /**
     * LOGIN
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIOCOMISSAO);
        $model->setAttributes(\Yii::$app->request->post());
        $model->loginCpfCnpj();
        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }

    /**
     * MAIN - SALDO
     */
    public function actionSaldo()
    {
        $post = \Yii::$app->request->post();
        return json_encode(['saldo' => $this->getSaldoAtual($post['auth_key'])]);
    }

    /**
     * Esqueceu a senha
     */
    public function actionNovaSenha()
    {
        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIOCOMISSAO);
        $model->setAttributes(\Yii::$app->request->post());
        $user = $model->getUserByCpfCnpj();

        $email = '';
        if (!empty($user['email'])) {
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
            $new_password = strtoupper(substr(uniqid(), -5));
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
     * VENDAS - FILTRO
     */
    public function actionVendasFilter()
    {
        $post = \Yii::$app->request->post();
        $empresas = CB04EMPRESA::getEmpresasComissao(User::getIdByAuthKey($post['auth_key']));
        return json_encode(['empresas' => $empresas, 'ultimosMeses' => $this->ultimosMeses(6)]);
    }

    /**
     * VENDAS - LISTA
     */
    public function actionVendasList()
    {
        $vendas = '';
        $post = \Yii::$app->request->post();
        if (($idUser = User::getIdByAuthKey($post['auth_key'])) && ($periodo = $post['periodo']) && ($empresa = $post['empresa'])) {
            $vendas = VIEWEXTRATO::comissaoVendasEmpresa($idUser, $empresa, $periodo);
        }
        return json_encode($vendas);
    }

    /**
     * EXTRATO - FILTRO
     */
    public function actionExtratoFilter()
    {
        return json_encode(['ultimosMeses' => $this->ultimosMeses(6)]);
    }

    /**
     * EXTRATO - LISTA
     */
    public function actionExtratoList()
    {
        $extrato = '';
        $post = \Yii::$app->request->post();
        if (($idUser = User::getIdByAuthKey($post['auth_key'])) && ($periodo = $post['periodo'])) {
            $extrato = VIEWEXTRATO::comissaoExtratoPagamento($idUser, $periodo);
        }
        return json_encode($extrato);
    }

    /**
     * Alterar senha 
     */
    public function actionSenha()
    {
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
            $user->setAttribute('email_valid', 1);
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

    private function ultimosMeses($qtdMeses = 12)
    {
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

        for ($m = 0; $m < $qtdMeses; $m++) {

            $numMes = date('n') - $m;
            $ano = date('Y');

            if (!empty($voltaAno)) {
                $numMes = $numMes + ($voltaAno * 12);
            }

            if ($numMes == 0) {
                $numMes = 12;
                $voltaAno++;
            }

            if (!empty($voltaAno)) {
                $ano = $ano - $voltaAno;
            }

            $objMeses[$ano . '-' . $numMes] = $meses[$numMes] . "/" . $ano;
        }
        return $objMeses;
    }

}
