<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\CB06VARIACAO;

/**
 * API Empresa controller
 */
class ApiEmpresaController extends GlobalBaseController {

    private $user;
    public $url;

    public function __construct($id, $module, $config = []) {
        $this->user = \Yii::$app->user->identity;
        $this->url = \Yii::$app->request->hostInfo . '/cashback/frontend/web/';
        parent::__construct($id, $module, $config);
    }
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPromocao() {
        $promocao = CB06VARIACAO::getPromocao($this->url);
        return json_encode($promocao);
    }
}
