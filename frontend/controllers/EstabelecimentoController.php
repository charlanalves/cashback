<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\CB04EMPRESA;
use common\models\CB09FORMAPAGEMPRESA;
use common\models\CB05PRODUTO;
use common\models\CB11ITEMCATEGORIA;
use common\models\CB12ITEMCATEGEMPRESA;
use common\models\CB06VARIACAO;
use common\models\CB07CASHBACK;
use common\models\SYS01PARAMETROSGLOBAIS;

/**
 * Estabelecimento controller
 */
class EstabelecimentoController extends \common\controllers\GlobalBaseController {

    private $user = null;
    private $estabelecimento = null;

    public function __construct($id, $module, $config = []) {
        if (($identity = \Yii::$app->user->identity)) {
            $this->user = $identity;
            $this->estabelecimento = \common\models\GlobalModel::findTable('CB04_EMPRESA', 'CB04_ID = ' . $this->user->id_company)[0];
        }
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     */
//    public function actions() {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
//        ];
//    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function goLogin() {
        return $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/login'));
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->goLogin();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        $this->layout = 'main-login';

        if (!\Yii::$app->user->isGuest) {
            $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/produto'));
            return;
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIOESTABELECIMENTO;

        if ($model->load(Yii::$app->request->post()) && $model->loginCpfCnpj()) {
            return $this->goLogin();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goLogin();
    }

    public function actionPrincipal() {
        $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/produto'));
        return;
    }

    public function actionEmpresa() {
        $this->layout = 'smartAdminEstabelecimento';
        $salvo = null;

        $model = new CB04EMPRESA();
        $al = $model->attributeLabels();
        $dataEstabelecimento = $model->findOne($this->user->id_company);
        if (($post = Yii::$app->request->post())) {
            $salvo = $dataEstabelecimento->saveEstabelecimento($post);
        }

        $dataEstabelecimento = $dataEstabelecimento->getAttributes();
        $dataEstabelecimento["FORMA-PAGAMENTO"] = CB04EMPRESA::getFormaPagamento($this->user->id_company);
        $dataCategoria = CB04EMPRESA::findCombo('CB10_CATEGORIA', 'CB10_ID', 'CB10_NOME', 'CB10_STATUS=1');
        $dataFormaPagamento = CB04EMPRESA::findCombo('CB08_FORMA_PAGAMENTO', 'CB08_ID', 'CB08_NOME', 'CB08_STATUS=1');

        $dataEstabelecimento['CB04_FUNCIONAMENTO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_FUNCIONAMENTO']);
        $dataEstabelecimento['CB04_OBSERVACAO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_OBSERVACAO']);
        
        return $this->render('empresa', [
                    'tituloTela' => 'Empresa',
                    'usuario' => $this->user->attributes,
                    'estabelecimento' => $dataEstabelecimento,
                    'categorias' => $dataCategoria,
                    'formaPagamento' => $dataFormaPagamento,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }

    public function actionProduto() {
        $this->layout = 'smartAdminEstabelecimento';
        $salvo = null;

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataProduto = $model->getProdutoVariacao($this->user->id_company);
//        print_r('<pre>');
//        print_r($dataProduto);
//        exit();
        return $this->render('produto', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }

    public function actionProdutoAtivar($produto, $status) {
        $CB05PRODUTO = CB05PRODUTO::findOne($produto);
        $CB05PRODUTO->setAttribute('CB05_ATIVO', $status);
        return ($CB05PRODUTO->save()) ? '' : 'error';
    }

    public function actionProdutoForm($produto = null) {
        \Yii::$app->view->title = $maxProduto = "";
        $this->layout = 'empty';

        $dataProduto = [];

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataItemProduto = CB04EMPRESA::findCombo('CB11_ITEM_CATEGORIA', 'CB11_ID', 'CB11_DESCRICAO', 'CB11_STATUS=1 AND CB11_CATEGORIA_ID=' . $this->estabelecimento['CB04_CATEGORIA_ID']);

        if (is_numeric($produto)) {
            // dados do produto
            $dataProduto = $model
                    ->find()
                    ->where(['CB05_EMPRESA_ID' => $this->user->id_company, 'CB05_ID' => $produto])
                    ->orderBy('CB05_NOME_CURTO')
                    ->one();
            $dataProduto = $dataProduto->getAttributes();
            $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
            $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

            // itens selecionados
            $dataProduto["ITEM-PRODUTO"] = CB05PRODUTO::getItem($produto);
        } else {
            $qtdMaxProduto = (int) SYS01PARAMETROSGLOBAIS::getValor('3');
            $qtdProduto = CB05PRODUTO::find()->where(['CB05_EMPRESA_ID' => $this->user->id_company])->count();
            if ($qtdMaxProduto <= $qtdProduto) {
                $maxProduto = "Você atingiu o limite de produtos do sistema.";
            }
        }

        return $this->render('produtoForm', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'itemProduto' => $dataItemProduto,
                    'al' => $al,
                    'maxProduto' => $maxProduto,
        ]);
    }

    public function saveProduto($param) {
        $param['CB05_EMPRESA_ID'] = $this->user->id_company;
        $modelId = CB05PRODUTO::primaryKey()[0];
        $CB05PRODUTO = (empty($param[$modelId])) ? new CB05PRODUTO() : CB05PRODUTO::findOne($param[$modelId]);
        $CB05PRODUTO->saveProduto($param);
    }

    public function deleteProduto($produto) {
        //CB07CASHBACK::deleteAll(['CB07_PRODUTO_ID' => $produto]);
        //CB06VARIACAO::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        //CB12ITEMCATEGEMPRESA::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        CB05PRODUTO::deleteAll(['CB05_ID' => $produto]);
    }

    public function actionPromocaoForm($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $model = new CB06VARIACAO();
        $al = $model->attributeLabels();

        $qtdMaxPromocao = (int) SYS01PARAMETROSGLOBAIS::getValor('4');
        $qtdPromocao = CB06VARIACAO::find()->where(['CB06_PRODUTO_ID' => $produto])->count();
        $maxPromocao = ($qtdMaxPromocao <= $qtdPromocao) ? "Você atingiu o limite de promoções por produto." : "";

        return $this->render('promocaoForm', [
                    'tituloTela' => 'Promoção',
                    'usuario' => $this->user->attributes,
                    'produto' => ['CB06_PRODUTO_ID' => $produto],
                    'al' => $al,
                    'maxPromocao' => $maxPromocao,
        ]);
    }

    public function savePromocao($param) {
        $CB06VARIACAO = new CB06VARIACAO();
        $CB06VARIACAO->setAttributes($param);
        $CB06VARIACAO->save();
    }

    /*
     * Excluir o cashback e a variacao
     */

    public function deletePromocao($promocao) {
        CB07CASHBACK::deleteAll(['CB07_VARIACAO_ID' => $promocao]);
        CB06VARIACAO::deleteAll(['CB06_ID' => $promocao]);
    }

    public function actionCashbackForm($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $dataProduto = CB05PRODUTO::findOne($produto)->getAttributes();
        $dataVariacao = CB04EMPRESA::findCombo('CB06_VARIACAO', 'CB06_ID', 'CB06_DESCRICAO', 'CB06_PRODUTO_ID=' . $produto);

        $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
        $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

        return $this->render('cashbackForm', [
                    'tituloTela' => 'CASHBACK',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'variacao' => $dataVariacao
        ]);
    }

    public function actionCashbackGrid($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';
        $dataCashback = CB07CASHBACK::getCashback($produto);
        return $this->render('cashbackGrid', ['cashback' => $dataCashback]);
    }

    public function saveCashback($param) {
        $CB07CASHBACK = new CB07CASHBACK();
        $CB07CASHBACK->saveCashback($param);
    }

    public function deleteCashback($param) {
        $CB07CASHBACK = new CB07CASHBACK();
        $CB07CASHBACK->deleteCashback($param);
    }

}
