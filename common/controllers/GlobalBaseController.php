<?php

/**
*
* GlobalController
* Classe responsável por agrupar funções de uso global dos controllers
*
* NOTA:
* Gentileza não alterar as funções dessa classe,
* pois impactará em todos os controllers que a utilizam.
*
* Para modificações, sobrescreva o método desejado no controller específico.
*
* @author Charlan Santos
*/

namespace frontend\controllers;

use \app\models\GlobalModel as GlobalModel;
use Yii;
use yii\web\Controller;
use yii\base\Object;
use \app\modules\Seguranca\models\GridModel as GridModel;
use yii\di\Instance;

class GlobalBaseController extends Controller
{

   /**
    * Constantes utilizada nas consultas que retornam dados a serem utilizados
    * nos componentes combo e autocomplete
    */
    const ALIAS_ID_COMBO = 'ID';
    const ALIAS_TEXT_COMBO = 'TEXTO';

   /**
    * @var array Botões de ação do grid
    */
    protected $btns = [];
	
    /**
    * @var array Botões padrões das ações do grid
    */
    protected $btnsDefault = [];

   /**
    * @var string prefixo da função do modelo que recuperará os dados do grid
    */
    protected $prefixQueryFn = 'Query';

   /**
    * @var string prefixo da função do modelo que recuperará a configuração do grid
    */
    protected $prefixSettingsFn = 'Settings';
    
    /**
    * @var string prefixo da função do modelo que recuperará o xml do componente
    */
    protected $prefixGetXml = 'globalGetXml';
    
    /**
    * @var string prefixo da função do modelo que recuperará o xml do componente
    */
    protected $prefixXmlFn = 'Xml';
    
    /**
     * @var string Sobrescrever esse atributo com o nome da classe do modelo relacionado
    */
    protected $modelRelated = '';

    /**
     * @var O id da tabela Pai do último saveRelated executado
     */
    protected  $lastSaveRelatedId;

    /**
     * @var string Sobrescrever esse atributo com o nome da classe do modelo relacionado
     */
    protected $relatedModelNS;

    /**
     * @var string parametros do framework M7 utilizados na estrutura do Yii
     */
    protected static $M7Params = [
        'MMS_MODEL_SCENARIO' => 'default',

    ];

    /**
     * @var Ação atual usada na funcão de log do sistema
     */
    protected $enableLogAudit = '';
    
    public function getEnableLogAudit()
    {
        return $this->enableLogAudit;
    }
    

	public function __construct($id, $module)
	{
		$this->btnsDefault = [
            'editar' => '../libs/layoutMask/imgs/editar.png^'.Yii::t("app","Editar").'^javascript:Form.runAction("GlobalUpdate", true)^_self',
            'excluir' => '../libs/layoutMask/imgs/excluir.png^'.Yii::t("app","Excluir").'^javascript:Form.runAction("GlobalDelete", true)^_self',
		    'desativar' => '../libs/layoutMask/imgs/excluir.png^'.Yii::t("app","Excluir").'^javascript:Form.runAction("GlobalInactivate", true)^_self',
        ];


        parent::__construct($id, $module);
	}


    /*
     *
     *
     * @autor Vitor Silva
     *
     * @return
     *
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $rotaController = $action->controller->getRoute();

        return $this->actionValidaPermissaoAcao($rotaController);
    }

    public function actionValidaPermissaoAcao($rotaController)
    {
        $temPermissao = $this->seg()->validaPermissaoAcao($rotaController, $_SESSION['gid']);

        if ($temPermissao) {
            return parent::beforeAction($this->action);
        } else {
            $msg = Yii::t('app','Você não tem permissão para executar esta ação.');
            echo json_encode(array('msgPermissaoAction'=> $msg, 'tipo'=>'erro'));
        }
    }

   /*
   * Obtém o texto atual inserido do autocomplete
   *
   * @autor Charlan Santos
   *
   * @return string
   *
   */
   protected function getSeachText()
   {
    	$data = Yii::$app->request->get();
        return isset($data['mask']) ? $data['mask'] : '';
   }

   protected function globalGetGridData($gridXmlFn, $param, $throwException, $instanceModel)
   {
       return $this->globalCall($gridXmlFn, $param, $throwException, $instanceModel);
   }
   
   protected function globalGetTreeData($gridXmlFn, $param, $throwException, $instanceModel)
   {
       return $this->globalCall($gridXmlFn, $param, $throwException, $instanceModel);
   }

   /*
    * Retorna o cabeçalho do grid para ser usado no método dataDumpComponent::getXML()
    *
    * @autor Charlan Santos
    *
    * @param string $table
    * @param string $columnId
    * @param string $columnText
    * @param string $where
    * @param string $className - Classe que contém a função personalizada
    * @param string $functionName - Nome da função personalizada
    * @param string $textDefault - Texto padrão da primeira opção
    *
    * @return xml - xml do combo
    *
    */
    public function actionCombo($table, $columnId, $columnText, $where=null, $className=null, $functionName=null, $textDefault=null, $limit=null, $sqlCompleto = null, $selected = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');

        if(!empty($sqlCompleto)) {
            $data = GlobalModel::findBySql($sqlCompleto)->asArray()->all();
        } else if (!empty($className) && !empty($functionName)) {
            $data = $className::$functionName($table, $columnId, $columnText, $where);
        } else {
            $data = GlobalModel::findCombo($table, $columnId, $columnText, $where, $limit);
        }

        $dataList = '';
        if (!empty($data)){
            $dataList = \yii\helpers\ArrayHelper::map($data, GlobalModel::ALIAS_ID_COMBO, GlobalModel::ALIAS_TEXT_COMBO);
        }

        if (!isset($textDefault) && $selected === null) {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList);
        } else if ($selected !== null) {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, false, $selected);
        }else if ($textDefault=='false') {
           $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, false);
        }  else {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, $textDefault);
        }

        return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
    }

    /*actionComboByCustomFn
     *
     * Retorna o cabeçalho do grid para ser usado no método dataDumpComponent::getXML()
     * Através de uma função personalizada
     * @autor Charlan Santos
     *
     * @param string $className - Classe que contém a função personalizada
     * @param string $functionName - Nome da função personalizada
     *
     * @return xml - xml do combo
     *
     */
    public function actionComboByCustomFn($className= null, $functionName= null, $where = null, $params = null)
    {
        return $this->actionCombo(
            null,
            null,
            null,
            $where,
            $className,
            $functionName
        );
    }


    /*
     * Retorna o cabeçalho do grid para ser usado no método dataDumpComponent::getXML()
     *
     * @autor Charlan Santos
     *
     * @param string $table
     * @param string $columnId
     * @param string $columnText
     * @param string $where
     * @param string $className - Classe que contém a função personalizada
     * @param string $functionName - Nome da função personalizada
     *
     * @return xml - xml do combo autocomplete
     *
     */
    public function actionAutocomplete($table, $columnId, $columnText, $where=null, $className=null, $functionName=null)
    {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');

    	$searchText = $this->getSeachText();

		if (!empty($className) && !empty($functionName)) {
        	$data = $className::$functionName($table, $columnId, $columnText, $searchText, $where);
    	} else {
    	 	$data = GlobalModel::findAutocomplete($table, $columnId, $columnText, $searchText, $where);
    	}

       $dataList = '';
    	if (!empty($data)){
    		$count = count($data);
    		for($i = 0; $i < $count; $i++) {
    			$value = $data[$i][GlobalModel::ALIAS_TEXT_COMBO];
    			$data[$i][GlobalModel::ALIAS_TEXT_COMBO] = preg_replace('/[!@#$%&*()-+=ªº^~,.:;?<>°ºª\x00-\x1f\"\'\{\}\[\]\(\)]/', '', $value);
    		}

        	$dataList = \yii\helpers\ArrayHelper::map($data, GlobalModel::ALIAS_ID_COMBO, GlobalModel::ALIAS_TEXT_COMBO);
		}

        $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, false);

        return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
    }



    public function setHeaderXml()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');
    }

    /*
     * Seta os botões que aparecerão no grid
     *
     * @autor Charlan Santos
     *
     * @param array $result - multidimensional com dados a serem exibido no grid
     *                ex retorno de yii\db\DataReader->readAll()
     * @param array $btns - retorno de getActions
     *
     * @return array
     *
     */
    protected function setBtnsGrid(&$result, $btns)
    {

        foreach($result as $k => $data) {

            foreach ($btns as $btnId => $btn) {
                $result[$k][$btnId] = $btn;
            }
        }
    }

    /*
     * Retorna os botões que aparecerão no grid
     *
     * @autor Charlan Santos
     *
     * @param array $actions ex: ['editar', 'excluir']
     *
     * @return array
     */
    protected function getBtnsGrid(array $actions)
    {
		$this->btns = array_merge($this->btnsDefault, $this->btns);
       return array_intersect_key($this->btns, array_flip($actions));
    }

    /*
     * Retorna o cabeçalho do grid para ser usado no método dataDumpComponent::getXML()
     *
     * @autor Charlan Santos
     *
     * @param string $gridNameFn - Nome da função que retorna a configuração do grid
     * @param yii\db\ActiveRecord $instanceModel
     *
     */
    protected function globalGetConfigGridHeader($gridNameFn, yii\db\ActiveRecord $instanceModel, $params ='')
    {
        $config = [];

        $gridSettings = $this->globalCall($gridNameFn, $params, true, $instanceModel);

        foreach ($gridSettings as $k => $data) {

            if (!empty($data['sets'])  ||  !empty($data['filter']) ) {
                $config['header'][0][] = $data['sets'];

                if (isset($data['filter'])) {
                    $config['header'][1][] = $data['filter'];
                }
            } else if (!empty($data['btnsAvailable'])) {
                $config['btnsAvailable'] = $data['btnsAvailable'];            
            } else if (!empty($data['afterInit'])){
                $config['afterInit'] = $data['afterInit'];
            }
        }

        return $config;
    }

    public function actionGlobalCrud($action, $params = null)
    {

        if (is_null($params)) {
            $params = Yii::$app->request->post();
        }

		$arrayRetorno = ['message' => '', 'status' => true];

		$this->setM7DefaultValues($params);

        try {
            $retorno = $this->globalCall($action, $params, true);
			if (is_array($retorno)) {
				$arrayRetorno = array_merge($arrayRetorno, $retorno);
			}

        } catch (\Exception $e) {
			$arrayRetorno = ['message' => $e->getMessage(), 'status' => false];

        }

        exit(json_encode($arrayRetorno));
    }

    private function setM7DefaultValues($params)
    {
        if(!empty($params['MMS_RELATED_MODEL'])) {
            $this->relatedModel= $params['MMS_RELATED_MODEL'];
        }

        foreach (self::$M7Params as $k => $v) {
            if(!empty($params[$k])) {
                self::$M7Params[$k] = $params[$k];
            }
        }

    }

    protected function globalSave($data, $onlySafe = false)
    {
        if (!empty($data['id'])) {
            $this->globalUpdate($data);
        } else {
            $this->globalCreate($data, $onlySafe);
        }
    }
    
    protected function globalCreate($data, $onlySafe = false)
    {
        $this->relatedModel = new $this->relatedModel();
        $this->relatedModel->scenario = self::$M7Params['MMS_MODEL_SCENARIO'];        
        $this->relatedModel->setAttributes($data, $onlySafe);
        $this->relatedModel->enableLogAudit = ($this->enableLogAudit === true) ? true : false;
        $this->relatedModel->save();
    }

    
    /**
     * globalCreateH
     * Caso Update:
     * Inativa o registro e cria um novo com objetivo de manter o histórico
     * Caso Create:
     * Apenas cria o registro
     *
     * @author Charlan Santos
     * @package GMO
     * @since  06/2017
     * @param array $data - Dados com key o nome da tabela e valor com o conteúdo a ser adicionado
     * @return void
     **/
    public function globalCreateH($data)
    {
        if (!empty($data['id'])) {
            $this->gInactivate($data);
            $this->globalCreate($data);
        } else {
            $this->globalCreate($data);
        }
    }
    
    /**
    * getFnComponentName
    * Retorna o padrão do nome dos médotos de configuração dos componentes no modelo
    * 
    * @access protected
    * @author Charlan Santos
    * @package Controller
    * 
    * @param string $componentName Apelido dado ao componente na view, é compativél com grid ou tree
    * @since  09/2017
    * @return true / Exception
    **/
    protected function getFnComponentName($componentType, $componentName)
    {
        $namesModelFn = [];
        
        $componentName = ucfirst($componentName);
        $namesModelFn['queryFn'] = $componentType .$this->prefixQueryFn . $componentName;
        $namesModelFn['settings'] = $componentType . $this->prefixSettingsFn . $componentName;
        $namesModelFn['xmlFn'] = $componentType . $this->prefixXmlFn . $componentName;
        
        return $namesModelFn;
    }
    
    /**
    * validateRelatedModel
    * Verifica se o atributo relatedModel esta vazio
    * 
    * @access private
    * @author Charlan Santos
    * @package Controller
    *
    * @since  09/2017
    * @return array - em caso de erro ['message' => $message, 'status' => false]
    **/
    private function validateRelatedModel()
    {
        if (empty($this->relatedModel)) {
            $errorMsg = ['message' => ['dev' => "O atributo \$modelRelated esta vazio. Sobrescreva-o no controller filho: ".get_called_class()." com o nome do modelo relacionado." ]];
            $message = \Yii::$app->v->getErrorMsgCurrentEnv($errorMsg);

            exit(json_encode(['message' => $message, 'status' => false]));
        }
    }
    
    /**
    * actionGlobalRead
    * Método centralizador que obtém o xml do componente informado pelo parametro 
    * $component
    *
    * @access public
    * @author Charlan Santos
    * @package Controller
     * 
    * @param string $gridName Apelido dado ao componente na view, é compativél com grid ou tree apesar do nome do parametro ser gridName
    * @param array|string $param Parâmetro a ser utilizado na consulta contida no modelo
    * @param boolean $json Se $param for um json passar true para fazer o decode automaticamente
    * @param string $component nome do componente. Compatível com Grid ou Tree
    * @since  09/2017
    * @return true / Exception
    **/
    public function actionGlobalRead($gridName, $param = '', $json = false, $component = 'Grid')
    {       
        
        $componentName = $gridName;
        
        // Realiza um tratamento Caso o atributo relatedModel não exista
       $this->validateRelatedModel();
       
       $namesModelFn = $this->getFnComponentName($component, $componentName);
       
       $this->setHeaderXml();
       
       // Se Json true realiza o decode dos parametros
        if ($json) {
            $param = is_array($param) ? $param : json_decode($param, true) ? : $param;
        }

       $fn = $this->prefixGetXml.$component;
       return $this->{$fn}($namesModelFn, new $this->relatedModel, $param, $json); 
    }

    /*
    * Obtém os dados em xml do grid dinamicamente.
    *
    * @autor Charlan Santos
    *
    * @param array $nameModelFn - Nome das funções que tem a consulta e gridSettings
    * Ex: [
    *       'queryFn' => 'nomeFuncaoQueRecuperaDadosDoGrid',
    *       'settings' => 'nomeFuncaoQueRecuperaConfigDoGrid'
    *      ]
    * Ou uma string com o sufixo da função no modelo. Nesse caso o prefixo será padrão:
    *  gridQuery + Sufixo
    *  gridSettings + Sufixo
    *
    * @param yii\db\ActiveRecord $instanceModel
    *
    * @return xml - dados em xml do grid
    *
    */
   public function globalGetXmlGrid($nameModelFn, yii\db\ActiveRecord $instanceModel, $params = '', $json = false)
   {   
       $result = $this->globalGetGridData($nameModelFn['queryFn'], $params, true, $instanceModel);

       $configGrid = $this->globalGetConfigGridHeader($nameModelFn['settings'], $instanceModel, $params);

       if (!empty($configGrid['btnsAvailable'])) {
           $btns = $this->getBtnsGrid($configGrid['btnsAvailable']);

           unset($configGrid['btnsAvailable']);

           $this->setBtnsGrid($result, $btns);
       }

       $xml = Yii::$app->dataDumpComponent->getXML($result, $configGrid );

       return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
   }
   
    /*
    * globalGetXmlTree
    * Obtém os dados em xml do grid dinamicamente.
    *
    * @autor Charlan Santos
    *
    * @param array $nameModelFn - Nome das funções que tem a consulta e gridSettings
    * Ex: [
    *       'queryFn' => 'nomeFuncaoQueRecuperaDadosDoComponet',
    *      ]
    * Ou uma string com o sufixo da função no modelo. Nesse caso o prefixo será padrão:
    *  treeQuery + Sufixo
    * 
    * @param yii\db\ActiveRecord $instanceModel
    *
    * @return xml - dados em xml do grid
    *
    */
   public function globalGetXmlTree($nameModelFn, yii\db\ActiveRecord $instanceModel, $params = '')
   {
        $result = $this->globalGetTreeData($nameModelFn['queryFn'], $params, true, $instanceModel);
        if (method_exists($instanceModel, $nameModelFn['xmlFn'])) {
            $xml = $instanceModel->{$nameModelFn['xmlFn']}($result);
        } else {
            $xml = Yii::$app->dataDumpComponent->getXmlTreeview($result);
        }
       return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
   }
   
    protected function globalUpdate($data)
    {
        $model = $this->globalCall('findOne', $data['id'], true, new $this->relatedModel);
        $model->setAttributes($data, false);
        $model->scenario = self::$M7Params['MMS_MODEL_SCENARIO'];
        $model->enableLogAudit = ($this->enableLogAudit === true) ? true : false;
        $model->save();
    }

    protected function globalDelete($data)
    {
        if (!empty($data['inactivateModel'])) {
            $deleteModel = $this->relatedModelNS .'\\'. $data['inactivateModel'];
        }else {
            $deleteModel = $this->relatedModel;
        }

        $model = $this->globalCall('findOne', $data['id'], true, new $deleteModel);
        $model->scenario = self::$M7Params['MMS_MODEL_SCENARIO'];
        $model->enableLogAudit = ($this->enableLogAudit === true) ? true : false;
        $returnQuery = $model->delete();

        Yii::$app->v->isFalse(['returnQuery' => $returnQuery],'','app', true);
    }
    
    /**
    * globalDeleteMultiple
    * Deleta multiplos registros utilizando ActiveRecord
    *
    * @access Protected
    * @author Charlan SantosEduardo M. Pereira
    * @package Controller
     * 
    * @param array $dados Um array com os ids a serem excluidos
    * @param boolean $transacao Se a operação será transacionada
    * @param boolean $log Se será armazenado log
    * @since  09/2017
    * @return true / Exception
    **/
    protected function globalDeleteMultiple($dados, $transacao = true, $log = false)
    {
        if (!is_array($dados)) {
            throw new \Exception(Yii::t('app', 'Erro interno. variável $data enão é um array'));
        }
        if ($this->enableLogAudit == "") {
            $this->enableLogAudit = $log;
        }
        
        try {
            if ($transacao){
                $transaction = \Yii::$app->db->beginTransaction();
            }
            
           foreach ($dados   as $id) {
               $this->globalDelete(['id' => $id]);
           }
            
           if ($transacao){
                $transaction->commit();
            }
            return true;
      } catch(\Exception $e) {
           if ($transacao){
                $transaction->rollBack();
           }
         throw $e;
      }
       
    }

    protected function globalInactivate($data)
    {
        if (!empty($data['inactivateModel'])) {
            $inactivateModel = $this->relatedModelNS .'\\'. $data['inactivateModel'];
        }else {
            $inactivateModel = $this->relatedModel;
        }

        $model = $this->globalCall('findOne', $data['id'], true, new $inactivateModel);
        $model->{$model->colFlagAtivo()} = 0;
        $model->scenario = self::$M7Params['MMS_MODEL_SCENARIO'];
        $model->enableLogAudit = ($this->enableLogAudit === true) ? true : false;
        $model->save();
    }
    
    protected function gInactivate($data)
    {
       $this->globalInactivate($data);
    }

    protected function gDelete($data)
    {
        $this->globalDelete($data);
    }

    /**
	* globalGridControlado
	* retorna estrutura do grid cadastrado no banco de dados de acordo com a action e o grupo do usuário
	*
	* @access Protected
	* @author Eduardo M. Pereira
	* @package Controller
	* @since  02/2017
	* @return array / Exception
	**/
    protected function globalGridControlado()
    {
		$gridJson = GridModel::getGrid(['ACTION'=>Yii::$app->controller->getRoute(), 'GRUPO'=>Yii::$app->session['gid']]);
		if ($gridJson) {
			eval('$return = ' . (($gridJson['JSON_GRUPO']) ? : $gridJson['JSON_PADRAO']) . ';');
			return $return;
		} else {
			return new \Exception(Yii::t('app','A Grid não foi cadastrada'));

		}
    }

    /**
     * actionCombomms
     * retorna estrutura do combo pegando as informacoes do banco de dados cadastrados na MMS20_AUTO_COMPLETE
     *
     * @access Public
     * @author Vitor Hallais
     * @package Controller
     * @since  03/2017
     * @return xml - xml do combo
     **/
    public function actionCombomms($component = 'combo', $where = '')
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');
        $get = Yii::$app->request->get();

        $xmlVazio = '<?xml version="1.0" encoding="utf-8"?><complete></complete>';

        if (isset($get['mask'])) $term = $get['mask'];
        if (isset($get['codigo'])) $codigo = $get['codigo'];

        else return $xmlVazio; // retorna nada se nao tiver o codigo (MMS20_COD_AUTOCOMPLETE) passado para buscar o autocomplete do banco
    
        $autocomplete = GlobalModel::findTable('MMS20_AUTO_COMPLETE','MMS20_COD_AUTOCOMPLETE=\''.$codigo.'\'');
    
        if(!count($autocomplete)) { // se nao achou o autocomplete do banco, retorna nada
            return $xmlVazio;
        }
        
        $autocomplete = $autocomplete[0];
        
        if ( empty($autocomplete['MMS20_TXT_WHERE']) && !empty($where)) {
            $autocomplete['MMS20_TXT_WHERE'] = ' '. $where;
        } else if(!empty($autocomplete['MMS20_TXT_WHERE']) && !empty($where)) {
            $autocomplete['MMS20_TXT_WHERE'] = ' AND '. $where;
        }

        if ($component == 'combo') {

            return $this->actionCombo(
                $autocomplete['MMS20_NM_TABELA_ORIGEM'],
                $autocomplete['MMS20_NM_CAMPO_CHAVE'],
                $autocomplete['MMS20_NM_CAMPO_VALOR'],
                $autocomplete['MMS20_TXT_WHERE'],
                null,
                null,
                null,
                null,
                $autocomplete['MMS20_SQL_COMPLETO']
            );
        }

        return $this->actionAutocomplete(
            $autocomplete['MMS20_NM_TABELA_ORIGEM'],
            $autocomplete['MMS20_NM_CAMPO_CHAVE'],
            $autocomplete['MMS20_NM_CAMPO_VALOR'],
            $autocomplete['MMS20_TXT_WHERE'],
            null,
            null,
            null,
            null,
            $autocomplete['MMS20_SQL_COMPLETO']
        );
    }

    /**
     * actionAutocompletemms
     * retorna estrutura do autcomplete pegando as informacoes do banco de dados cadastrados na MMS20_AUTO_COMPLETE
     *
     * @access Public
     * @author Charlan Santos
     * @package Controller
     * @since  05/2017
     * @return xml - xml do combo autocomplete
     **/
    public function actionAutocompletemms()
    {
        return $this->actionCombomms('autoComplete');
    }


    /**
     * globalImportFilesOneTable
     * Importa para o banco de dados um arquivo de um formato específico
     * Esse método é usado em conjunto com o "type file" do Dhtmlx e DhtmlxForm.SendMMS()
     *
     * @access Public
     * @author Charlan Santos
     * @package Controller
     *
     * @param string $model nome da classe do modelo
     * @param array $cols nome colunas do banco que o arquivo será salvo na ordem que aparece no arquivo
     * @param array $skipCols posicao das colunas do arquivo que serão ignoradas
     * @param boolean $skipFirstLine ignorar primeira linha do arquivo (geralmente cabeçalhos)
     * @param string $fileFormat formato do arquivo que será importado
     *
     * @return \Exception|true
     **/
    public function globalImportFilesOneTable($model = "GlobalModel", $cols = [], $skipCols = [], $skipFirstLine = true, $fileFormat = 'xls')
    {
        // Verifica se os parâmetros foram passados via $_POST e os obtém
        $this->getPostImportParams( $model, $cols, $skipCols, $fileFormat );

        // Se os parametros obrigatórios não forem informados lança uma Exception
        $this->validateFnParams( $cols );

       if ( $this->existColFlagAtivoFn( $model ) ) {

           $filePath = Yii::$app->File->getFile('tmp_name', 'O envio do arquivo é Obrigatório');

           $fileName = Yii::$app->File->getFile('name');

           $fileArray = Yii::$app->File->fileToArray( $fileName, $filePath, $fileFormat );

           $this->executeBeforeImportRules( $fileArray, $cols, $skipCols );

           $fileArray = $this->setImportAtributes( $fileArray, $cols, $skipCols );

           $globalModel = new $model();

           if ( $skipFirstLine ) {
               array_shift($fileArray);
           }

           return $globalModel->saveMultiple($fileArray, $model, $model::colFlagAtivo());
         }
    }



    /**
     * globalImportFilesRelated
     * Importa para o banco de dados um arquivo e cria referencia
     *
     * @access Public
     * @author Eduardo M. Pereira
     * @package Controller
     *
     * @param string $modelReference nome do modelo de referencia com namespace
     * @param array $dataReference dados da referencia [nome_campo => valor]
     * @param string $modelRelated nome do modelo relacionado (este que recebe os dados do arquivo)
     * @param string $attributeRelated campo no modelo relacionado que amarra a referencia
     * @param array $cols nome colunas do banco que o arquivo será salvo na ordem que aparece no arquivo
     * @param array $skipCols posicao das colunas do arquivo que serão ignoradas
     * @param boolean $skipFirstLine ignorar primeira linha do arquivo (geralmente cabeçalhos)
     * @param string $fileFormat formato do arquivo que será importado
     * @param boolean $transacao controla transacao do banco de dados
     * @param string $scenarioModelRelated cenario do modelo relacionado para regras especificas da importacao
     * @param boolean $allError exbi todos os erros do arquivo caso contrario apenas o primeiro e para a importacao
     *
     * @return \Exception|true
     **/
    public function globalImportFilesRelated($modelReference, $dataReference, $modelRelated = "GlobalModel", $attributeRelated, $cols = [], $skipCols = [], $skipFirstLine = true, $fileFormat = 'xls', $transacao = true, $scenarioModelRelated = null, $allError = false)
    {

        // Verifica se os parâmetros foram passados via $_POST e os obtém
        $this->getPostImportParams( $modelRelated, $cols, $skipCols, $fileFormat );

        // Se os parametros obrigatórios não forem informados lança uma Exception
        $this->validateFnParams( $cols );

        $filePath = Yii::$app->File->getFile('tmp_name', 'O envio do arquivo é Obrigatório');

        $fileName = Yii::$app->File->getFile('name');

        $fileArray = Yii::$app->File->fileToArray( $fileName, $filePath, $fileFormat );

        $this->executeBeforeImportRules( $fileArray, $cols, $skipCols );

        $fileArray = $this->setImportAtributes( $fileArray, $cols, $skipCols );

        if ( $skipFirstLine ) {
        	array_shift($fileArray);
        }

        $data = [
			0 => $dataReference,
			1 => $fileArray
        ];

        $reference = new $modelReference();
        return $reference->saveRelated($data, [$modelRelated => $attributeRelated], true, $transacao, $scenarioModelRelated, $allError);

    }


    /**
     * validateFnParms
     * Valida os parametros do método globalImportFilesOneTable
     *
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param $cols
     *
     * @return \Exception|true
     **/
    private function validateFnParams($cols)
    {
        if ( empty($cols) ) {
            throw new \Exception(Yii::t("app",
                "Não foi informado as colunas que serão salvas na importação"
            ));
        }

    }

    /**
     * getPostImportParams
     * Obtém os parametros via $_POST
     *
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param $model
     * @param $cols
     * @param $skipCols
     * @param $fileFormat
     *
     * @return mixed parametros setados por referência
     **/
    private function getPostImportParams(&$model, &$cols, &$skipCols, &$fileFormat)
    {
       $params = json_decode(Yii::$app->request->post('params'), true);

       if ( !empty($params) && count($params > 0) ) {
           foreach($params as $param => $v){
               $$param = $v;
           }
       }
    }


    protected function executeBeforeImportRules(&$fileArray, $cols, $skipCols) {}

    /**
     * setImportAtributes
     * Monta um array chave valor para ser usado no método AR setAttributes()
     *
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param array $fileArray
     * @param array $cols
     * @param array $skipCols
     *
     * @return array
     **/
    private function setImportAtributes($fileArray, $cols, $skipCols)
    {
        $totalLn = count($fileArray);

        if  (!empty($skipCols)) {
            rsort($skipCols);
            for ( $i = 0; $i < $totalLn; $i++ ) {
                $this->skipImportCols($skipCols, $fileArray[$i], $cols);

                $this->executeRule($fileArray[$i], $fileArray, $i);

                $fileArray[$i] = array_combine(array_values($cols), array_values($fileArray[$i]));
            }
        } else {
            for ( $i = 0; $i < $totalLn; $i++ ) {
                $fileArray[$i] = array_combine(array_values($cols), array_values($fileArray[$i]));
            }
        }

        return $fileArray;
    }

    /**
     * executeRule
     * Ao montar um array chave valor para ser usado no método AR setAttributes()
     * Executa uma regra personalizada a ser definida na subclasse
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param array $currentLine - Linha corrente do foreach
     * @param array $arrayData - O array completo
     * @param integer $key - a key atual do foreach
     *
     * @return void $arrayData setados por referência
    **/
    protected function executeRule($currentLine, &$arrayData, $key) {}

    /**
     * skipImportCols
     * Ignora colunas do arquivo ao realizar a importacao
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param array $skipCols - Linha corrente do foreach
     * @param array $fileArray - O array completo
     * @param array $cols - a key atual do foreach
     *
     * @return void $arrayData setados por referência
    **/
    private function skipImportCols($skipCols, &$fileArray, &$cols)
    {
        foreach ($skipCols as $skip) {
            unset($fileArray[$skip]);
        }
    }

    /**
     * existColFlagAtivoFn
     * Verifica se existe o metodo colGlagAtivo definido na subclasse
     *
     * @access Private
     * @author Charlan Santos
     * @package Controller
     *
     * @param GlobalModel $model - Model que será verificado
     *
     * @return \Exceptoion|true
    **/
    private function existColFlagAtivoFn($model)
    {
      $methodExists = method_exists($model, 'colFlagAtivo');

      if (!$methodExists) {
           throw new \Exception(Yii::t("app",
               "O método colFlagAtivo não existe no modelo \"" . $model . "\".
               Crie-o em no modelo retornando uma
               string com o nome da coluna FLG_ATIVO"
           ));
      }

      return true;
    }

    protected function globalExportExcel($param)
	{
		$grid = $param['grid'];
		$param = $param['param'];
		$param = is_array($param) ? $param : json_decode($param, true) ? : $param;

		$filename = 'excelFileName' . $grid;
		$header = 'gridSettings' . $grid;
		$dataDefault = 'gridQuery' . $grid;
		$dataExportExcel = 'gridQueryExportExcel' . $grid;

        $model = new $this->relatedModel;

		$filename = (property_exists($model, $filename)) ? $model->{$filename} : 'excel';
		$filename .= '.xls';

		$dados		= (method_exists($model, $dataExportExcel)) ? $model->{$dataExportExcel}($param) : $model->{$dataDefault}($param);
		$header	= $model->{$header}();

		// ----------------------------------------
		// processa dados ---------------------

		$table_content = $conteudo_header = $conteudo_linha = $linha_width = $linha_align = "";
		$colValidas = [];

		// Cabecalho ---------------------------
		foreach($header as $colunas) {
			if (empty($colunas['sets']['hiddenExcel']) && empty($colunas['btnsAvailable'])) {
				$linha_vlr = (empty($colunas['sets']['title'])) ? "" : $colunas['sets']['title'];
				$linha_width = (empty($colunas['sets']['width'])) ? "" : "width = \"" . $colunas['sets']['width'] . "\"";
				$linha_align = (empty($colunas['sets']['align'])) ? "" : "align = \"" . $colunas['sets']['align'] . "\"";
				if (!empty($colunas['sets']['id'])) {
					$colValidas[] = $colunas['sets']['id'];
				}
				$conteudo_header .= "<th " . $linha_width . $linha_align . ">" . $linha_vlr . "</th>\n";
			}
		}
		$table_content .= "<thead><tr>" . $conteudo_header . "</tr></thead>";


		// Linhas ----------------------------------
		foreach($dados as $colunas) {
			$conteudo_linha_col = '';
			foreach($colValidas as $idCol) {
				$conteudo_linha_col .= (!empty($colunas[$idCol])) ? "<td>" . $colunas[$idCol] . "</td>" : "<td></td>";
			}
			$conteudo_linha .= "<tr>" . $conteudo_linha_col . "</tr>\n";
		}
		$table_content .= "<tbody>" . $conteudo_linha . "</tbody>";


		// monta toda a pagina
		$excel = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
				<head>
					<meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\"/>
				</head>
				<body>
					<table border=\"1\" width=\"100%\">
						$table_content
					</table>
				</body>
			</html>";

		exit(json_encode(['excel' => $excel, 'fileName' => $filename]));
	}

	/**
	 * actionGerarRelatorio
	 * Retorna o relatório anteriormente publicado no servidor do Jasper
	 *
	 * @access Public
	 * @author Charlan Santos
	 * @package Controller
	 *
	 * @param $format string pdf ou xls
	 * @param $params array ['NOME_PARAM' => 'VALOR_PARAM']
	 *
	 * @return array [
	 *         'file' => binário do arquivo ou null,
	 *         'status' => boolean,
	 *         'message' => string ou null
         * ]
	 *         
	 **/
	public function actionGerarRelatorio($fileName = '', $params = '', $format = 'pdf')
	{
	    $message = '';
	    $status = true;
	    $file = null;

	    try {

	        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-type', 'application/json');

            if ( empty( $_REQUEST['fileName'])  || $_REQUEST['fileName'] == 'false' ) {
                throw new \Exception(\Yii::t('app', 'O nome do relatório é obrigatório.'));
            }
            
            if ( !empty( $_REQUEST['format']) ) {
                $format = $_REQUEST['format'];
            }
            
            $fileName = $_REQUEST['fileName'];

            $parametros = '';

            if ( !empty( $_REQUEST['params'] )) {
        	    $parametros = $_REQUEST['params'];
            }

            $rel = \app\modules\Relatorios\models\RelatoriosModel::findOne(['MMS11_COD_RELATORIO' => $fileName]);

           if (is_null($rel)) {
               throw new \Exception(\Yii::t('app', 'Não existe relatório com o código: '.$fileName));
           }

           echo \Yii::$app->Jasper->loadReport($parametros, $rel->MMS11_NM_ARQUIVO_CONFIG, $format);

	    } catch (\Exception $e) {

	        $message = $e->getMessage();
	        $status = false;

    	    exit(json_encode(['status' => $status, 'message' => $message]));
	    }

	}

	/**
	 *
	 *
	 *
	 * @access Public
	 * @author Charlan Santos
	 * @package Controller
	 *
	 * @param
	 *
	 **/
	public function globalSaveRelated($dados)
	{
	    $strModel = '\\app\modules\\'.$this->module->id.'\\models\\'.$dados['PARAMS']['modelPai'];

	    $dados['PAIS_FILHOS'][0][0]['ECM31_DT_INCLUSAO'] = date("d/m/Y");
            $totalPF = count($dados['PAIS_FILHOS']);
	    
          for($i = 0; $i < $totalPF; $i++) {
              $pModel = new $strModel;

              if ( $this->existColFlagAtivoFn( $pModel ) ) {
              
                  if ($dados['PARAMS']['flgAtivo'] === true){
                        $dados['PARAMS']['flgAtivo'] = $pModel::colFlagAtivo();
                  }
                  
                  $dados['PARAMS']['scenarioModelFilho'] = null;

                  $pc[0] = $dados['PAIS_FILHOS'][$i][0];

                  if (isset($dados['PAIS_FILHOS'][$i][1][0][0])) {
                    $pc[1] = $dados['PAIS_FILHOS'][$i][1][0];
                  } else {
                      $pc[1] = $dados['PAIS_FILHOS'][$i][1];
                  }

                  $pModel->saveRelated(
                      $pc,
                      $dados['PARAMS']['relacao'],
                      $dados['PARAMS']['flgAtivo'],
                      $dados['PARAMS']['transacao'],
                      $dados['PARAMS']['scenarioModelFilho']
                  );
              }
       }

      $this->lastSaveRelatedId = $pModel->{$pModel->primaryKey()[0]};
    }
    
    /**
     * 
     * 
     *
     * @access Public
     * @author Charlan Santos
     * @package Controller
     *
     * @param	 
     * 
     **/
    public function globalSaveMultiple($dados)
    {        
        if (empty($dados['SM']['dados'])) {
            throw new \Exception(Yii::t('app', 'Os dados não foram informados. O padrão esperado é: $dados["SM"]["dados"]'));
        }
        
        if (empty($dados['SM']['model'])) {
            throw new \Exception(Yii::t('app', 'Os dados não foram informados. O padrão esperado é: $dados["SM"]["model"]'));
        }
        
        if (empty($dados['SM']['flgAtivo'])) {
           $dados['SM']['flgAtivo'] = true;
        }
        
        if (empty($dados['SM']['transacao'])) {
           $dados['SM']['transacao'] = true;
        }
        
        $strModel = '\\app\modules\\'.$this->module->id.'\\models\\'.$dados['SM']['model'];
        $model = new $strModel();
        $model->saveMultiple(            
            $dados['SM']['dados'],
            $dados['SM']['model'],
            $dados['SM']['flgAtivo'],
            $dados['SM']['transacao']
        );
        
    }
    
    public function globalCall($action, $data, $returnThowException = true, $class = NULL)
	{
	    if (empty($class)) {
	        $class = $this;
	    }

	    $methodExists = method_exists($class, $action);
	    Yii::$app->v->isFalse(['methodExists' => $methodExists],['dev' => 'o método '.$action.' não existe.'],'app', $returnThowException);

	    return call_user_func_array([$class, $action], [$data]);
	}	
	
	/**	
	 * Carrega o grid
	 *
	 * @access Public
	 * @author Charlan Santos
	 * @package Controller
	 *
	 * @param array $data ['grid' => 'Main', 'param1' => '', 'param2' => '']
	 * @return true / Exception
	 *
	 **/
	public function globalLoadGrid($data)
	{
	   if (empty($data['grid'])) {
            throw new \Exception(Yii::t('app', 'O parâmetro grid esta vazio.'));
        }
        
       return $this->actionGlobalRead($data['grid'], json_encode($data), true);
	}
}
