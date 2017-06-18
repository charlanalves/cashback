<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\CB16PEDIDO;

/**
 * API Iugu Controller
 */
class ApiIuguController extends GlobalBaseController {

    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
   
    
    public function criarSalvarContaCliente() 
    {
       \Yii::$app->Iugu->execute('criarSalvarContaCliente', \Yii::$app->request->post('dados'));
    }
    
    public function criarSalvarContaEmpresa() 
    {
       \Yii::$app->Iugu->execute('criarSalvarContaEmpresa', \Yii::$app->request->post('dados'));
    }
    
    public function actionProcessarTransacao()
    {
       \Yii::$app->Iugu->execute('prepararTransacao', \Yii::$app->request->post('dados'));
       \Yii::$app->Iugu->execute('processarTransacao');
    }
    
  
 
    
}
