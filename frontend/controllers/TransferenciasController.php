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
		
		$this->btns =  [];
	
        parent::__construct($id, $module);
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

  
   public function callMethodDynamically($action, $data, $returnThowException = true, $class = NULL)
   {   
       if (empty($class)) {
          $class = $this;
       }

       $methodExists = method_exists($class, $action);
        Yii::$app->v->isFalse(['methodExists' => $methodExists],'','app', $returnThowException);

       return call_user_func_array([$class, $action], [$data]);
   }
}
