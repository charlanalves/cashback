<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\LoginForm;
use common\models\CB06VARIACAO;
use common\models\CB10CATEGORIA;
use common\models\VIEWSEARCH;

/**
 * API Empresa controller
 */
class ApiEmpresaController extends GlobalBaseController {

    public $url;
    
    public function __construct($id, $module, $config = []) {
        $this->url = \Yii::$app->request->hostInfo . '/cashback/frontend/web/';
        parent::__construct($id, $module, $config);
    }
    
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
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
        $CB06VARIACAO = CB06VARIACAO::getPromocao($this->url);
        return json_encode($CB06VARIACAO);
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

}
