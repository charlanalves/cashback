<?php

namespace frontend\controllers;

use Yii;
use \common\controllers\GlobalBaseController as BaseController;

/**
 *
 */
class TransferenciasController extends BaseController
{

   /**
    * @var string Habilita validação Csrf 
   */
    public $enableCsrfValidation = false;
 
   /**
    * @var string modelo relacionado com o controler 
    */
    public $relatedModel = "common\models\TransferenciasModel";
    
    
    public function __construct($id, $module)
	{
       	
        parent::__construct($id, $module);
         $this->btnsDefault = [
            'editar' => '../web/dxassets/dhtmlx/terrace/imgs/transfer2.png^' . Yii::t("app", "Transferir") . '^javascript:C7.runAction("DoTransfer")^_self',
        ];
	} 

    public function actionIndex()
    { 
        $request = Yii::$app->request;
        $get = $request->get();

    	$this->layout = "//emptyLayout";

        $model = new $this->relatedModel; 
        $al = $model->attributeLabels();
		
		echo $this->renderFile('@app/libs/system/formObjJs.php');
        echo $this->renderFile('@frontend/views/transferencias/_form.php', ['al' => $al]);

        return $this->render('index', ['tituloTela'=> $get['tituloTela']]);
    }
   public function realizaSaques($id) 
    {	
       \Yii::$app->Iugu->realizaSaques($id['id']);
    }
  
   public function callMethodDynamically($action, $data, $returnThowException = true, $class = NULL)
   {   
       if (empty($class)) {
          $class = $this;
       }

       $methodExists = method_exists($class, $action);
        Yii::$app->v->isFalse(['methodExists' => $methodExists],'','app', $returnThowException);

       return call_user_func_array([$class, $action], [$data]);
   }
   
  public function fazerTodasTrans()
  { 
  	   $pedido = \common\models\CB16PEDIDO::getTransLiberadas();
       \Yii::$app->Iugu->execute('criaTransferencias', ['pedido' => $pedido]);
  	
  }
}
