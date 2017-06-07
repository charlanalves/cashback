<?php

/**
 * This is the template for generating a CRUD controller class file.
 */
use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use \common\controllers\GlobalBaseController as BaseController;

/**
 *
 */
class <?= $controllerClass ?> extends <?= "BaseController" . "\n" ?>
{

   /**
    * @var string Habilita validação Csrf 
   */
    public $enableCsrfValidation = false;
 
   /**
    * @var string modelo relacionado com o controler 
    */
    public $relatedModel = "<?= $generator->nsModel.'\\'.$modelClassName?>";
    
    
    public function __construct($id, $module)
	{
		
		$this->btns =  [];
	
        parent::__construct($id, $module);
	} 

    public function actionIndex()
    { 
        $request = Yii::$app->request;
        $get = $request->get();

    	$this->layout = "//<?= $generator->dhtmlxLayout ?>";

        $model = new $this->relatedModel; 
        $al = $model->attributeLabels();
		
		echo $this->renderFile('@app/libs/system/formObjJs.php');
        echo $this->renderFile(<?= "'".$generator->pathViewForm."'" ?>, ['al' => $al]);

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
