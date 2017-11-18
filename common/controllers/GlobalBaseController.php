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

namespace common\controllers;

use common\models\GlobalModel as GlobalModel;
use \Yii;
use yii\web\Controller;
use yii\base\Object;
use yii\di\Instance;

class GlobalBaseController extends Controller {

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
    protected $prefixQueryFn = 'gridQuery';

    /**
     * @var string prefixo da função do modelo que recuperará a configuração do grid
     */
    protected $prefixSettingsFn = 'gridSettings';

    /**
     * @var string Sobrescrever esse atributo com o nome da classe do modelo relacionado
     */
    protected $modelRelated = '';

    
    public function __construct($id, $module) {
        $this->btnsDefault = [
            'editar' => '../libs/layoutMask/imgs/editar.png^' . Yii::t("app", "Editar") . '^javascript:Form.runAction("GlobalUpdate", true)^_self',
            'excluir' => '../libs/layoutMask/imgs/excluir.png^' . Yii::t("app", "Excluir") . '^javascript:Form.runAction("GlobalDelete", true)^_self',
        ];

        parent::__construct($id, $module);
    }

    
    /*
     * Obtém o texto atual inserido do autocomplete
     *
     * @autor Charlan Santos
     *
     * @return string
     *
     */

    protected function getSeachText() {
        $data = Yii::$app->request->get();
        return isset($data['mask']) ? $data['mask'] : '';
    }

    /*
     * Obtém os dados em xml do grid dinamicamente.
     *
     * @autor Charlan Santos
     *
     * @param array $nameModelFn - Nome das funções que tem a consulta e gridSettings
     * Ex: [
     *       'gridXmlFn' => 'nomeFuncaoQueRecuperaDadosDoGrid',
     *       'gridSettings' => 'nomeFuncaoQueRecuperaConfigDoGrid'
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

    public function globalGetXmlGrid($nameModelFn, yii\db\ActiveRecord $instanceModel, $params = '', $json = false) {
        $configGrid = '';

        /* Verfica se foi informado o nome completo ou somente o sufixo das funções que serão chamadas do modelo ou */
        if (!is_array($nameModelFn)) {
            $nameModelFn = ucfirst($nameModelFn);

            $namesModelFn['gridXmlFn'] = $this->prefixQueryFn . $nameModelFn;
            $namesModelFn['gridSettings'] = $this->prefixSettingsFn . $nameModelFn;
        }

        $this->setHeaderXml();

        if ($json) {
            $params = is_array($params) ? $params : json_decode($params, true) ? : $params;
        }

        $result = $this->globalGetGridData($namesModelFn['gridXmlFn'], $params, true, $instanceModel);

        $configGrid = $this->globalGetConfigGridHeader($namesModelFn['gridSettings'], $instanceModel);

        if (!empty($configGrid['btnsAvailable'])) {
            $btns = $this->getBtnsGrid($configGrid['btnsAvailable']);

            unset($configGrid['btnsAvailable']);

            $this->setBtnsGrid($result, $btns);
        }

        $xml = Yii::$app->dataDumpComponent->getXML($result, $configGrid);
        
        echo $xml;
        //$this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
    }

    private function globalGetGridData($gridXmlFn, $param, $throwException, $instanceModel) {
        return $this->callMethodDynamically($gridXmlFn, $param, $throwException, $instanceModel);
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

    public function actionCombo($table, $columnId, $columnText, $where = null, $className = null, $functionName = null, $textDefault = null, $limit = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');

        if (!empty($className) && !empty($functionName)) {
            $data = $className::$functionName($table, $columnId, $columnText, $where);
        } else {
            $data = GlobalModel::findCombo($table, $columnId, $columnText, $where, $limit);
        }
        
        $dataList = '';
        if (!empty($data)) {
            $dataList = \yii\helpers\ArrayHelper::map($data, GlobalModel::ALIAS_ID_COMBO, GlobalModel::ALIAS_TEXT_COMBO);
        }

        if (!isset($textDefault)) {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList);
        } else if ($textDefault == 'false') {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, false);
        } else {
            $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, $textDefault);
        }

        return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
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

    public function actionAutocomplete($table, $columnId, $columnText, $where = null, $className = null, $functionName = null) {
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
        if (!empty($data)) {
            $count = count($data);
            for ($i = 0; $i < $count; $i++) {
                $value = $data[$i][GlobalModel::ALIAS_TEXT_COMBO];
                $data[$i][GlobalModel::ALIAS_TEXT_COMBO] = preg_replace('/[!@#$%&*()-+=ªº^~,.:;?<>°ºª\x00-\x1f\"\'\{\}\[\]\(\)]/', '', $value);
            }

            $dataList = \yii\helpers\ArrayHelper::map($data, GlobalModel::ALIAS_ID_COMBO, GlobalModel::ALIAS_TEXT_COMBO);
        }

        $xml = Yii::$app->dataDumpComponent->getXmlCombo($dataList, false);

        return $this->renderPartial('@app/views/default/xmlMask', array("xml" => $xml));
    }

    public function setHeaderXml() {
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

    protected function setBtnsGrid(&$result, $btns) {

        foreach ($result as $k => $data) {

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

    protected function getBtnsGrid(array $actions) {
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

    protected function globalGetConfigGridHeader($gridNameFn, yii\db\ActiveRecord $instanceModel) {
        $config = [];

        $gridSettings = $this->callMethodDynamically($gridNameFn, '', true, $instanceModel);

        foreach ($gridSettings as $k => $data) {

            if (!empty($data['sets']) || !empty($data['filter'])) {
                $config['header'][0][] = $data['sets'];

                if (isset($data['filter'])) {
                    $config['header'][1][] = $data['filter'];
                }
            } else if (!empty($data['btnsAvailable'])) {
                $config['btnsAvailable'] = $data['btnsAvailable'];
            }
        }

        return $config;
    }

    public function actionGlobalCrud($action, $params = null) {

        if (is_null($params)) {
            $params = Yii::$app->request->post();
        }

        $message = '';
        $status = true;

        try {
            $this->callMethodDynamically($action, $params, true);
        } catch (\Exception $e) {

            $message = $e->getMessage();
            $status = false;
        }

        exit(json_encode(['message' => $message, 'status' => $status]));
    }

    protected function globalCreate($data) {
        $model = new $this->relatedModel();
        $model->setAttributes($data, false);
        $model->save();
    }

    public function actionGlobalRead($gridName, $param = '', $json = false) {

        if (empty($this->relatedModel)) {
            $errorMsg = ['message' => ['dev' => "O atributo \$modelRelated esta vazio. Sobrescreva-o no controller filho: " . get_called_class() . " com o nome do modelo relacionado."]];
            $message = \Yii::$app->v->getErrorMsgCurrentEnv($errorMsg);

            exit(json_encode(['message' => $message, 'status' => false]));
        }

        return $this->globalGetXmlGrid($gridName, new $this->relatedModel, $param, $json);
    }

    protected function globalUpdate($data) {
        $model = $this->callMethodDynamically('findOne', $data['id'], true, new $this->relatedModel);
        $model->setAttributes($data, false);
        $model->save();
    }

    protected function globalDelete($data) {

        $model = $this->callMethodDynamically('findOne', $data['id'], true, new $this->relatedModel);
        $returnQuery = $model->delete();

        Yii::$app->v->isFalse(['returnQuery' => $returnQuery], '', 'app', true);
    }

    protected function globalInactivate($data) {
        $inactivateModel = isset($this->inactivateModel) ? : $this->relatedModel;

        $model = $this->callMethodDynamically('findOne', $data['id'], true, new $inactivateModel);
        $model->{$model->colFlagAtivo()} = 0;
        $model->save();
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
     * */
    protected function globalGridControlado() {
        $gridJson = GridModel::getGrid(['ACTION' => Yii::$app->controller->getRoute(), 'GRUPO' => Yii::$app->session['gid']]);
        if ($gridJson) {
            eval('$return = ' . (($gridJson['JSON_GRUPO']) ? : $gridJson['JSON_PADRAO']) . ';');
            return $return;
        } else {
            return new \Exception(Yii::t('app', 'A Grid não foi cadastrada'));
        }
    }

    /**
     * actionAutocompletemms
     * retorna estrutura do autcomplete pegando as informacoes do banco de dados cadastrados na MMS20_AUTO_COMPLETE
     *
     * @access Public
     * @author Vitor Hallais
     * @package Controller
     * @since  03/2017
     * @return xml - xml do combo autocomplete
     * */
    public function actionAutocompletemms() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-type', 'text/xml');
        $get = Yii::$app->request->get();

        $xmlVazio = '<?xml version="1.0" encoding="utf-8"?><complete></complete>';

        if (isset($get['mask']))
            $term = $get['mask'];
        if (isset($get['codigo']))
            $codigo = $get['codigo'];
        else
            return $xmlVazio; // retorna nada se nao tiver o codigo (MMS20_COD_AUTOCOMPLETE) passado para buscar o autocomplete do banco

        $autocomplete = GlobalModel::findTable('MMS20_AUTO_COMPLETE', 'MMS20_COD_AUTOCOMPLETE=\'' . $codigo . '\'')[0];

        if (empty($autocomplete)) { // se nao achou o autocomplete do banco, retorna nada
            return $xmlVazio;
        }

        return $this->actionAutocomplete(
                        $autocomplete['MMS20_NM_TABELA_ORIGEM'], $autocomplete['MMS20_NM_CAMPO_CHAVE'], $autocomplete['MMS20_NM_CAMPO_VALOR'], $autocomplete['MMS20_TXT_WHERE']
        );
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
     * */
    public function globalImportFilesOneTable($model = "GlobalModel", $cols = [], $skipCols = [], $skipFirstLine = true, $fileFormat = 'xls') {
        // Verifica se os parâmetros foram passados via $_POST e os obtém
        $this->getPostImportParams($model, $cols, $skipCols, $fileFormat);

        // Se os parametros obrigatórios não forem informados lança uma Exception
        $this->validateFnParams($cols);

        if ($this->existColFlagAtivoFn($model)) {

            $filePath = Yii::$app->File->getFile('tmp_name', 'O envio do arquivo é Obrigatório');

            $fileName = Yii::$app->File->getFile('name');

            $fileArray = Yii::$app->File->fileToArray($fileName, $filePath, $fileFormat);

            $this->executeBeforeImportRules($fileArray, $cols, $skipCols);

            $fileArray = $this->setImportAtributes($fileArray, $cols, $skipCols);

            $globalModel = new $model();

            if ($skipFirstLine) {
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
     * 
     * @return \Exception|true 
     * */
    public function globalImportFilesRelated($modelReference, $dataReference, $modelRelated = "GlobalModel", $attributeRelated, $cols = [], $skipCols = [], $skipFirstLine = true, $fileFormat = 'xls', $transacao = true, $scenarioModelRelated = null) {

        // Verifica se os parâmetros foram passados via $_POST e os obtém
        $this->getPostImportParams($modelRelated, $cols, $skipCols, $fileFormat);

        // Se os parametros obrigatórios não forem informados lança uma Exception
        $this->validateFnParams($cols);

        $filePath = Yii::$app->File->getFile('tmp_name', 'O envio do arquivo é Obrigatório');

        $fileName = Yii::$app->File->getFile('name');

        $fileArray = Yii::$app->File->fileToArray($fileName, $filePath, $fileFormat);

        $this->executeBeforeImportRules($fileArray, $cols, $skipCols);

        $fileArray = $this->setImportAtributes($fileArray, $cols, $skipCols);

        if ($skipFirstLine) {
            array_shift($fileArray);
        }

        $data = [
            0 => $dataReference,
            1 => $fileArray
        ];

        $reference = new $modelReference();
        return $reference->saveRelated($data, [$modelRelated => $attributeRelated], true, $transacao, $scenarioModelRelated);
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
     * */
    private function validateFnParams($cols) {
        if (empty($cols)) {
            throw new \Exception(Yii::t("app", "Não foi informado as colunas que serão salvas na importação"
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
     * */
    private function getPostImportParams(&$model, &$cols, &$skipCols, &$fileFormat) {
        $params = json_decode(Yii::$app->request->post('params'), true);

        if (!empty($params) && count($params > 0)) {
            foreach ($params as $param => $v) {
                $$param = $v;
            }
        }
    }

    protected function executeBeforeImportRules(&$fileArray, $cols, $skipCols) {
        
    }

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
     * */
    private function setImportAtributes($fileArray, $cols, $skipCols) {
        $totalLn = count($fileArray);

        if (!empty($skipCols)) {
            rsort($skipCols);
            for ($i = 0; $i < $totalLn; $i++) {
                $this->skipImportCols($skipCols, $fileArray[$i], $cols);

                $this->executeRule($fileArray[$i], $fileArray, $i);

                $fileArray[$i] = array_combine(array_values($cols), array_values($fileArray[$i]));
            }
        } else {
            for ($i = 0; $i < $totalLn; $i++) {
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
     * */
    protected function executeRule($currentLine, &$arrayData, $key) {
        
    }

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
     * */
    private function skipImportCols($skipCols, &$fileArray, &$cols) {
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
     * */
    private function existColFlagAtivoFn($model) {
        $methodExists = method_exists($model, 'colFlagAtivo');

        if (!$methodExists) {
            throw new \Exception(Yii::t("app", "O método colFlagAtivo não existe no modelo \"" . $model . "\".
               Crie-o em no modelo retornando uma 
               string com o nome da coluna FLG_ATIVO"
            ));
        }

        return true;
    }

    protected function globalExportExcel($param) {
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

        $dados = (method_exists($model, $dataExportExcel)) ? $model->{$dataExportExcel}($param) : $model->{$dataDefault}($param);
        $header = $model->{$header}();

        // ----------------------------------------
        // processa dados ---------------------

        $table_content = $conteudo_header = $conteudo_linha = $linha_width = $linha_align = "";
        $colValidas = [];

        // Cabecalho --------------------------- 
        foreach ($header as $colunas) {
            if (empty($colunas['sets']['hiddenExcel'])) {
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
        foreach ($dados as $colunas) {
            $conteudo_linha_col = '';
            foreach ($colValidas as $idCol) {
                $conteudo_linha_col .= "<td>" . $colunas[$idCol] . "</td>";
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
    
    public function callMethodDynamically($action, $data, $returnThowException = true) {
        $methodExists = method_exists($this, $action);
        Yii::$app->v->isFalse(['methodExists' => $methodExists], '', 'app', $returnThowException);
        call_user_func_array([$this, $action], [$data]);
    }

}
