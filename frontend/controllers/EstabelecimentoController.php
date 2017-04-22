<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\CB04EMPRESA;
use common\models\CB09FORMAPAGEMPRESA;

/**
 * Estabelecimento controller
 */
class EstabelecimentoController extends \common\controllers\GlobalBaseController {

    private $user;

    public function __construct($id, $module, $config = []) {
        $this->user = (\Yii::$app->user->identity)? : null;
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
            $dataEstabelecimento->setAttributes($post);
            $salvo = ($dataEstabelecimento->save()) ? true : false ;
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

}
