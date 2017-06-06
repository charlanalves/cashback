<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\gii\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;


/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DefaultController extends Controller
{
    public $layout = 'generator';
    /**
     * @var \yii\gii\Module
     */
    public $module;
    /**
     * @var \yii\gii\Generator
     */
    public $generator;


    public function actionIndex()
    {
        
        $this->layout = 'main';

        return $this->render('index');
    }

    public function actionTeste($id, $method, $params = '')
    {	
        $generator = $this->loadGenerator($id);
        
        if (empty($params)) {
        	echo json_encode(call_user_func(array($generator, $method)));
        } else {
        	echo json_encode(call_user_func_array(array($generator, $method), array($params)));
        }
        die();
    }
    
    
    public function actionView($id) 
    {  
        $generator = $this->loadGenerator($id);
        $params = ['generator' => $generator, 'id' => $id];
 
        $preview = Yii::$app->request->post('preview');
        $generate = Yii::$app->request->post('generate');
        $answers = Yii::$app->request->post('answers');

        if ($preview !== null || $generate !== null) {
            if ($generator->validate()) {
                $generator->saveStickyAttributes();
                $files = $generator->generate();
                if ($generate !== null && !empty($answers)) {
                    $params['hasError'] = !$generator->save($files, (array) $answers, $results);
                    $params['results'] = $results;
                } else {
                    $params['files'] = $files;
                    $params['answers'] = $answers;
                }
            }
        }

        return $this->render('view', $params);
    }
    
    

 public function actionTestSql()
 {
     try {
         
         $query =  Yii::$app->request->get('query');
         $message = 'A query foi executada com sucesso';
         $status = true;
         $result = '';
         
         if ( empty( $query ) ) {
             throw  new \Exception('A query está vazia');
         }
        
         
         $connection = \Yii::$app->db;
         $command = $connection->createCommand( $query );
         $reader = $command->query();
         
         $result = $reader->readAll();
         
     } catch (\Exception $e) {
         $message = $e->getMessage();
         $status = false;
     }
   
     exit(json_encode(['message' => $message, 'status' => $status, 'results' => $result]));
 }
    
 public function actionView2($id) 
 {   
        $attributes = [];
        $preview = '';
        
        try {
                $generate =  Yii::$app->request->post('generator');
                if (!empty($generate)) {
                    foreach ($generate as $k => $value) {
                        $attributes['generator'][preg_replace('/[^\w]|(Generator)/', '', $value['name'])] = $value['value'];
                    }
                }
                
                $answers =  Yii::$app->request->post('answers');
                if (!empty($answers)) {
                    foreach ($answers as $k => $value) {
                        $attributes['answers'][preg_replace('/[^\w]|(answers)/', '', $value['name'])] = $value['value'];
                    }
                }    
                
                
                $generator = $this->loadGenerator($id);
                
                
                $params = ['generator' => $generator, 'id' => $id];    
                
                $generator->setAttributes($attributes['generator']);
                
                
                if ($preview !== null || $generate !== null) {
                    if (!$generator->validate()) {
                        $errormsg = array_values($generator->getFirstErrors())[0];
                        throw new  \yii\base\UserException($errormsg);
                    }
                    
                    $generator->saveStickyAttributes();                
                    $files = $generator->generateWithQueryCustom();
                    if ($generate !== null && !empty($answers)) {
                        $params['hasError'] = !$generator->save($files, (array) $attributes['answers'], $results);
                        $params['results'] = $results;
                    } else {
                        $params['files'] = $files;
                        $params['answers'] = $answers;
                    }
                
                }
        
            
            } catch (\Exception $e) {
                ob_clean();
                $params['hasError'] = true;
                
                if ($e instanceof \yii\base\UserException) {
                    $params['results'] = $e->getMessage();
                } else {
                    $templateException = Yii::$app->errorHandler->previousExceptionView;
                    $params['results'] = Yii::$app->errorHandler->renderFile($templateException, ['exception' => $e]);
                }
            }
            
        return $this->renderPartial('preview', $params);
    }
    
    public function actionGetJsonFormSettings($id)
    {
        $attributes = [];
        $preview = '';
        $body = '';
        $vars = '';
        $message ='';
        $status = true;
        $formData = '';
    
        try {
            $form =  json_decode(Yii::$app->request->get('grid'), true);
            
            $generator = $this->loadGenerator($id);
            
            $generator->formSettings = $form;
            
            $generator->generateForm();
            
            
            if (!empty($generator->columnsTypeJs)) {
                foreach ($generator->columnsTypeJs as $keyForm => $columnsTypeJs) { 
           
                    $vars .= $generator->getTextVarsComboForm($keyForm);
                    
                    $header = '
                         [
            				 {type: "settings", position: "label-top", labelAlign: "left", labelWidth: "AUTO"},
                           	 {type:"fieldset",  offsetTop:0, label: this.settings.subtitleWindow, width:253, list:[
                         ';
                    
                    foreach ($columnsTypeJs as $k => $component) { 
                         foreach ($component as $k2 => $js) {
                              $body .= $component[$k2]." \n"; 
                          }
                    } 
                }
			     $bottom = '		
			         {type: "button", name:"create", value: "Salvar", className: "buttom-window-right"}
       			       ]}
    	               ];
                    }
		           ';
			     
			     $formData = ' /*' .$header . $body . $bottom . '*/';
            }
            
            
    $a=1;
        } catch (\Exception $e) {
            $status = false;
            $params['hasError'] = true; 
            
            $message = $e->getMessage();
        }
    
       exit(json_encode(['status' => $status, 'message' => $message, 'formData' => $formData]));
    }

//     public function actionGrid()
//     {   
//         Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
//         $headers = Yii::$app->response->headers;
//         $headers->add('Content-type', 'text/xml');
//         $request = Yii::$app->request;
//         $data = json_decode($request->get('tbandcols'), true);
        
//         if (empty($data)) {
//             return false;
//         }
        
        
//         $gridData = array();
        
//         $count = count($data);
        
//         for ($i = 0; $i < $count; $i++){
            
//              $gridData[$i]['tabela'] = $data[$i]['table'];
//              $gridData[$i]['nome_campo'] = $data[$i]['column'];
//              $gridData[$i]['tipo_campo'] = 'Input Text';
//              $gridData[$i]['nome_coluna'] = $data[$i]['column'];
//         }
        
//         $xml = Yii::$app->dataDumpComponent->getXML($gridData, $this->getConfigGridHeader());
        
//         return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
        
//     }
    public function actionGrid()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');
        $request = Yii::$app->request;
        $data = $request->post('data');
    
        if (empty($data)) {
            return false;
        }
    
    
        $gridData = array();
    
        $count = count($data);
    
        for ($i = 0; $i < $count; $i++){
    
            $gridData[$i]['tabela'] = $data[$i]['table'];
            $gridData[$i]['nome_campo'] = $data[$i]['column'];
            $gridData[$i]['tipo_campo'] = 'Input Text';
            $gridData[$i]['nome_coluna'] = $data[$i]['column'];
        }
    
        $xml = Yii::$app->dataDumpComponent->getXML($gridData, $this->getConfigGridHeader());
    
        return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
    
    }
    function group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public function getConfigGridHeader()
    {   
        $config['header'][0][0] = ['title' => 'Tabela', 'width'=>'300' , 'type'=>'ro' , 'sort'=>'str'];
        $config['header'][0][1] = ['title' => 'Nome do Campo', 'width'=>'200' , 'type'=>'ro' , 'sort'=>'str'];
        $config['header'][0][2] = ['title'=> 'Tipo do Campo', 'width'=>'*', 'type'=>'combo', 'sort'=>'str', 'xmlcontent'=>"1",        
            'option'=> [
                ['value'=>'combo', 'text'=> 'Combo'],
                ['value'=>'autocomplete', 'text'=> 'AutoComplete'],
                ['value'=>'comboEcm21', 'text'=> 'Combo ECM21'],
                ['value'=>'calendar', 'text'=> 'Calendário'],
                ['value'=>'inputText', 'text'=> 'Input Text']
            ]
        ];
        
        $config['header'][0][3] = ['title' => 'Nome da coluna no grid', 'width'=>'*' , 'type'=>'ed' , 'sort'=>'str'];       
        $config['header'][1][0] = ['title'=>'#text_filter'];
        $config['header'][1][1] = ['title'=>'#text_filter'];        
        $config['header'][1][2] = ['title'=>'#text_filter'];
        $config['header'][1][3] = ['title'=>'#text_filter'];
        
       
        return $config;
        
    }
    
    public function actionPreview($id, $file)
    { 
        $generator = $this->loadGenerator($id);
      	//falta passar 2 parametros para a funcao abaixo tipo do arquivo e os demais parametros
      	// como nome do model e controller
        $file = $generator->getfile();
      	$content = $file->preview();
      
        if ($content !== false) {
            return  '<div class="content">' . $content . '</content>';
        }
        
        return '<div class="error">Preview não esta disponível para esse tipo de arquivo.</div>';
    }

    public function actionDiff($id, $file)
    {
        $generator = $this->loadGenerator($id);
        if ($generator->validate()) {
            foreach ($generator->generate() as $f) {
                if ($f->id === $file) {
                    return $this->renderPartial('diff', [
                        'diff' => $f->diff(),
                    ]);
                }
            }
        }
        throw new NotFoundHttpException("Code file not found: $file");
    }

    /**
     * Runs an action defined in the generator.
     * Given an action named "xyz", the method "actionXyz()" in the generator will be called.
     * If the method does not exist, a 400 HTTP exception will be thrown.
     * @param string $id the ID of the generator
     * @param string $name the action name
     * @return mixed the result of the action.
     * @throws NotFoundHttpException if the action method does not exist.
     */
    public function actionAction($id, $name)
    {   
        $generator = $this->loadGenerator($id);
        $method = 'action' . $name;
        if (method_exists($generator, $method)) {
            return $generator->$method();
        } else {
            throw new NotFoundHttpException("Unknown generator action: $name");
        }
    }

    /**
     * Loads the generator with the specified ID.
     * @param string $id the ID of the generator to be loaded.
     * @return \yii\gii\Generator the loaded generator
     * @throws NotFoundHttpException
     */
    protected function loadGenerator($id)
    {
        if (isset($this->module->generators[$id])) {
            $this->generator = $this->module->generators[$id];
            $this->generator->loadStickyAttributes();
            $this->generator->load(Yii::$app->request->post());

            return $this->generator;
        } else {
            throw new NotFoundHttpException("Code generator not found: $id");
        }
    }
}
