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
            $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/principal'));
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
        $this->layout = 'smartAdminEstabelecimento';
        return $this->render('index', ['v' => $this->user->attributes]);
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
        $dataProduto = $model
                ->find()
                ->where(['CB05_EMPRESA_ID' => $this->user->id_company])
                ->orderBy('CB05_NOME_CURTO')
                ->all();

        if (($post = Yii::$app->request->post())) {
            //$salvo = $dataProduto->saveProduto($post);
        }

        return $this->render('produto', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }

    public function actionProdutoForm($produto = null) {
        
        \Yii::$app->view->title = '';
        
        $this->layout = 'empty';
        $salvo = null;
        $dataProduto = [];
        
        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataItemProduto = CB04EMPRESA::findCombo('CB11_ITEM_CATEGORIA', 'CB11_ID', 'CB11_DESCRICAO', 'CB11_STATUS=1 AND CB11_CATEGORIA_ID=' . $this->estabelecimento['CB04_CATEGORIA_ID']);
        
        if (is_numeric($produto)) {
            $dataProduto = $model
                    ->find()
                    ->where(['CB05_EMPRESA_ID' => $this->user->id_company, 'CB05_ID' => $produto])
                    ->orderBy('CB05_NOME_CURTO')
                    ->all();
            $dataProduto = $dataProduto->getAttributes();
        }

        if (($post = Yii::$app->request->post())) {
            //$salvo = $dataProduto->saveProduto($post);
        }

        return $this->render('produtoForm', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'itemProduto' => $dataItemProduto,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }

}
