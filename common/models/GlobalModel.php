<?php

/**
* GlobalModel
* Classe responsável por agrupar funções de uso global dos Modelos
*
* NOTA:
* Gentileza não alterar as funções dessa classe, 
* pois impactará em todos os modelos que a utilizam.
*
* Para modificações, sobrescreva o método desejado no modelo específico.
* @author Charlan Santos
**/

namespace common\models;
use \yii\db\ActiveRecord;
use \yii\db\Exception;
use app\modules\Seguranca\models\LoginModel;

class GlobalModel extends ActiveRecord
{

    /**
     * Constantes utilizada nas consultas que retornam dados a serem utilizados
     * nos componentes combo e autocomplete
     */
    const ALIAS_ID_COMBO = 'ID';
    const ALIAS_TEXT_COMBO = 'TEXTO';
    const SCENARIO_CREATE = 'SCENARIO_CREATE';
    const SCENARIO_UPDATE = 'SCENARIO_UPDATE';
    const SCENARIO_DELETE = 'SCENARIO_DELETE';
    const SCENARIO_INACTIVATE = 'SCENARIO_INACTIVATE';
    const ACT_CREATE = 'C';
    const ACT_UPDATE = 'U';
    const ACT_DELETE = 'D';
    
    
    
    /**
     * Path para fazer upload de arquivo
    */
    public $globalPathFile = '';
                
    
    /**
     * Nome do arquivo do upload
    */
    public $globalFileName = '';
    
    
    /**
     * flag que indica se o upload do arquivo será do jasperReport ou normal
     */
    public $jasperUpload = false;
    
    /**
     * Descrição do arquivo que será salvo no jasperServer
    */
    public $globalFileDescJasper;
    
    
    /**
     * Categoria de tradução
    */
    public $i18nCat = "app";
    
    /**
     * Ação atual usada na funcão de log do sistema
     */
    public $gCurrentAction = "CREATE";
    
    /**
     * Ação atual usada na funcão de log do sistema
     */
    public $enableLogAudit = '';
    
        /**
     * Ação atual usada na funcão de log do sistema
     */
    const TB_LOG = 'MMS22_LOG_AUDITORIA';
    
    
    
    public function __construct($config = [])
    {
        if (!empty(\Yii::$app->controller->module->id)) {
            $this->i18nCat = \Yii::$app->controller->module->id;
        }
        parent::__construct($config);
    }
    
    /**
     * getMMS22_JSON_AUDITORIA
     * Obtem os campos para log com seus respectivos valores antigos e novos 
     *
     * @package GlobalModel
     * @param array $ca campos modificados
     * @param Active Record $lastModel modelo que realizou a operação 
     * 
     * @since  09/2017
     * @author Charlan Santos
     **/
    private function getMMS22_JSON_AUDITORIA($ca = '', $lastModel, $op)
    {   
        $attr = $lastModel->attributes;
        if (!empty($ca)){
            // removendo campos que não foram alterados
            $attr = array_intersect_key($attr, $ca);
        }
        
        $arrayLog = [];
        $c = 0;
        foreach( $attr as $campo => $valor )
        {
            $arrayLog[$c]['CAMPO'] = $campo;
            switch ($op) {
                case self::ACT_CREATE;
                    $arrayLog[$c]['VALOR_ANTIGO'] = '';
                    $arrayLog[$c]['VALOR_NOVO'] = $valor;
                break;
                case self::ACT_UPDATE;
                    $arrayLog[$c]['VALOR_ANTIGO'] = (!empty($ca[$campo]))? $ca[$campo]: $valor;
                    $arrayLog[$c]['VALOR_NOVO'] = $valor;
                break;
                case self::ACT_DELETE;
                    $arrayLog[$c]['VALOR_ANTIGO'] = $valor;
                    $arrayLog[$c]['VALOR_NOVO'] = '';
                break;
            }
            $c++;
        }

     return json_encode($arrayLog);
   
    
    }
    /**
    * @inheritdoc
    */
    public static function findCustom($table, $columnId, $columnText)
    {

        if (empty($table) || empty($columnId) || empty($columnText)) {
            return false;            
        }
        
        $query =  "SELECT $columnId, $columnText FROM $table";
        
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $reader = $command->query();
        
        return $reader->readAll();
    }
    
    /**
    * @inheritdoc
    */
    public static function findCombo($table, $columnId, $columnText, $whereCustom='', $limit)
    {
        if (empty($table) || empty($columnId) || empty($columnText)) {
            return false;
        }
        
        $whereCustom = ($whereCustom) ? "$whereCustom AND" : $whereCustom;

        $limit = isset($limit) ? 'ROWNUM <'.$limit : 'ROWNUM < 100';
        
        $query =  "SELECT DISTINCT $columnId AS ".self::ALIAS_ID_COMBO.", $columnText AS ".self::ALIAS_TEXT_COMBO." 
                    FROM (SELECT * FROM $table ORDER BY $columnText)
                    WHERE $whereCustom $limit
                    ORDER BY $columnText";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $reader = $command->query();

        return $reader->readAll();
    }
    
    /**
    * @inheritdoc
    * 
    */
    public static function findAutocomplete($table, $columnId, $columnText, $filter=null, $whereCustom=null)
    {
        if (empty($table) || empty($columnId) || empty($columnText)) {
            return false;
        }

        $where = "";
        
        if (!empty($filter)) { 
        	$where .= " UPPER(CONVERT(".$columnText.", 'US7ASCII')) LIKE CONVERT('".strtoupper($filter)."%', 'US7ASCII') AND ";
        }
		
        if (!empty($whereCustom)) { 
        	$where .= " ($whereCustom) AND ";
        }
        
        $query =  "SELECT DISTINCT $columnId AS ".self::ALIAS_ID_COMBO.", $columnText AS ".self::ALIAS_TEXT_COMBO." 
				   FROM (SELECT * FROM $table ORDER BY $columnText)
				   WHERE $where
						 ROWNUM < 100 
				   ORDER BY $columnText";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $reader = $command->query();

        return $reader->readAll();
    }
    
	
    /**
     * globalSaveLog
     * Salva na tabela de log estado atual e anterior dos registros
     *
     * @package GlobalModel
     * @since  07/2017
     * @author Charlan Santos
     **/
    private function globalSaveLog($changedAttributes)
    {
        $ctrlEnableLogAudit = \Yii::$app->controller->getEnableLogAudit();
        
        // Se setar o atributo enableLogAudit no modelo ele assume a preferência
        // do contrário obtém o valor definido no controller
       if 
        ( $this->enableLogAudit == '' && !empty( $ctrlEnableLogAudit )
        )
        {
            $this->enableLogAudit = $ctrlEnableLogAudit;
        }
        
      if( $this->enableLogAudit === true  && $this->tableName() != self::TB_LOG)
      {
          $log = new MMS22_LOG_AUDITORIAModel;
          
          $log->setAttributesLog($log, $this, $changedAttributes);
          
          $log->save();
          
          $this->gCurrentAction = '';
      }
    }


     /**
     * setAttributesLog
     * Salva na tabela de log estado atual e anterior dos registros
     *
     * @package GlobalModel
     * @since  07/2017
     * @param ActiveRecord $modelLog - model da tabela de log passado por referência
     * @param array $changedAttributes - Atributos que estavam persistidos no banco de dados antes do update
     * @return void
     * @author Charlan Santos
     **/
    private function setAttributesLog(ActiveRecord &$modelLog, $lastModel, $changedAttributes = '')
    {
        $modelLog->MMS22_SG_TAB_REFERENCIA = $lastModel->tableName();
        $modelLog->MMS22_ID_TAB_REFERENCIA = $lastModel->primaryKey;
        $modelLog->MMS22_ID_USUARIO = LoginModel::buscaIdUser(\Yii::$app->session['userId']);
        $modelLog->MMS22_TP_ACAO = $lastModel->gCurrentAction;
        $modelLog->MMS22_NM_MODULO = $this->i18nCat;
        $modelLog->MMS22_JSON_AUDITORIA = $this->getMMS22_JSON_AUDITORIA
        (
            $changedAttributes, 
            $lastModel,
            $lastModel->gCurrentAction 
        );
    }
    
    /**
     * afterSave
     * Reune operações a serem executas após salvar ou editar um registro 
     * no banco de dados
     *
     * @package GlobalModel
     * @since  07/2017
     * @author Charlan Santos
     * 
     * @param int $insert - identifica se foi uma exclusão ou atualização
     * @param array $changedAttributes - Atributos que estavam persistidos no banco de dados antes do update
     * @return string - retorna 'C' para create 'D' para delete 'U' para update 
     **/
    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);
        
        $op = $this->gSetCurretAction($insert);
        
        return $this->gExecuteAfterCrudOperations(                 
                $this, 
                $op,
                $changedAttributes
        );
    }
    
    /**
     * @inheritdoc
    */
    public function save($runValidation=true, $attributeName=null)
    {
       if(!parent::save($runValidation, $attributeName)){
            return $this->gExecuteAfterCrudOperations($this);
       }
       return true;
    }
    
    /**
     * gSetCurretAction
     * Define a ação atual
     * no banco de dados
     *
     * @package GlobalModel
     * @since  07/2017
     * @author Charlan Santos
     *
     * @param int $insert - identifica se foi uma exclusão ou atualização  
     * @return int - 1 para inclusão 0 para edição   
     **/
    private function gSetCurretAction($insert)
    {
        $op = self::ACT_CREATE;
        
        if (!$insert) {
            $op = self::ACT_UPDATE;
        }
        
        return $op;
    }
    
    /**
     * afterDelete
     * Reune operações a serem executas após deletar um registro
     * no banco de dados
     *
     * @package GlobalModel
     * @since  07/2017
     * @author Charlan Santos
     *   
     **/
    public function afterDelete()
    {
        parent::afterDelete();
    
        return $this->gExecuteAfterCrudOperations($this, self::ACT_DELETE);
    }
    
    /**
     * gExecuteAfterCrudOperations
     * Reune operações a serem executas após deletar um registro
     * no banco de dados
     *
     * @package GlobalModel
     * @since  07/2017
     * @author Charlan Santos
     *
     * @param int $op - identifica se foi uma exclusão ou atualização
     * @param array $changedAttributes - Atributos que estavam persistidos no banco de dados antes do update
     * @param ActiveRecord - $model - modelo envolvido
     **/
    private function gExecuteAfterCrudOperations($model,$op = null, $changedAttributes = '')
    {
        
        $this->gCurrentAction = $op;
        
        try {
        
            $modelErro = $model->getFirstErrors();
        
            if (!empty($modelErro)) {
                $errorMsg = ['message' => [
                    'dev' => array_values($modelErro)[0],
                    'prod' => array_values($modelErro)[0]],
                ];
            } else {
                $model->globalCheckAndUploadFiles();
                $model->globalSaveLog($changedAttributes);
            }
        
        
        } catch (\Exception $e) {
        
            $errorMsg = ['message' => ['dev' => $e->getMessage()]];
        }
        
        if (!empty($errorMsg)) {
            $errorMsg = \Yii::$app->v->getErrorMsgCurrentEnv($errorMsg);
            throw new \Exception($errorMsg);
        }
        
        return true;
    }
   
    protected function globalCheckAndUploadFiles()
    {        
        if (!empty($_FILES["filesMMS"]["tmp_name"]) && !empty($this->globalPathFile)) {
      
            $this->generateDefaultFileName();
            
            $this->doUploadFile();
        }
    }
    
    private function generateDefaultFileName()
    {
        if (empty($this->globalFileName)) {
            $idColumn = $this->getTableSchema()->primaryKey[0];
            $this->globalFileName = $this->$idColumn . '-' . $_FILES["filesMMS"]["name"];
        }
    }
    
    private function doUploadFile()
    {  
       if (empty($_FILES["filesMMS"]) || empty($_FILES["filesMMS"]["tmp_name"]) ) {
           throw new \Exception(\Yii::t('app', 'O envio do arquivo é obrigatório.'));
       }
           
       $tpName = $_FILES["filesMMS"]["tmp_name"];
              
        if ($this->jasperUpload || isset($_FILES["filesMMS"]["jasperUpload"])) {
             \Yii::$app->Jasper->uploadFile($tpName, $this->globalFileName, $this->globalFileDescJasper, $this->getIsNewRecord());
        } else {            
            $file = $this->globalPathFile.$this->globalFileName;
             
            if (!move_uploaded_file($tpName,  $file)) {
                throw new \Exception(Yii::t('app', 'O arquivo não pode ser salvo. verifique a permissão do diretório: '.$this->globalPathFile));
            }
        }
    }
        
    
   public function getSpecificScenario($scenario)
    {
        if (empty($scenario)) {
            return false;
        }
        
        $scenarioc = array();
        $rules = $this->rules();
        
        for ($i = 0; $i < count($rules); $i++){
            if (isset($rules[$i]['on'])) {
                if ($rules[$i]['on'][0] == $scenario) {
                    $scenarioc[$i]['fields'] =  $rules[$i][0];
                    $scenarioc[$i]['validator'] = $rules[$i][1];
                }
            }
        }
        
        return $scenarioc;
    }


	private function _saveMultiple($dados, $model=null, $relacao=null, $flgAtivo, $scenarioModelFilho = null)
	{
		try{

			preg_match('/.*(?<=\\\\)/si', get_class($this), $match);

			if (empty($match[0])) {
				return false;
			}

			$namespace = $match[0];

			if(!empty($relacao)) {
				$modelRelacao = $namespace . $relacao[0];
				$idRelacao = $relacao[1];
				$valorRelacao = $relacao[2];
				
			} else {
				$modelRelacao = $namespace . $model;
				
			    if (class_exists($model)) {
			        $modelRelacao = $model;
			    }
			    
				$idRelacao = null;
				$valorRelacao = null;
				
			}
			
			$pkModel = $modelRelacao::primaryKey()[0];
			
			
			foreach($dados as $key => $col) {
				if($idRelacao) {
					$col[$idRelacao] = $valorRelacao;
				}

				// testa PK, se não existir a chave no array cria novo registro
				// caso contrario edita se o registro for encontrado 
				if (empty($col[$pkModel])) {

					// testa flag ativo
					 if ($flgAtivo !== true ) {
						if ( array_key_exists( (string) $flgAtivo, $col ) ) {
							if ( empty( $col[$flgAtivo] ) ) {
								continue;
							}
						}
					}
					
					// retira a PK para add novo registro
					if ( array_key_exists( (string) $pkModel, $col ) ) {
						unset($col[$pkModel]);	
					}
					
					$m = new $modelRelacao();
					
				} else {
				    $m = $modelRelacao::findOne( $col[$pkModel] );
				    
					if ( empty( $m ) )  {
						if ($modelRelacao::saveMultipleRuleOnEdit([$pkModel => $col[$pkModel]], $col, $modelRelacao)) {
							continue;
						}
					}
				}
				
				if (!is_null($scenarioModelFilho)) {
				   $m->setScenario($scenarioModelFilho); 
				}
				
				$m->setAttributes($col);
				$m->save();
			}
		
        } catch (\Exception $e) {
            throw $e;
        }
		
	}
	
	protected function saveMultipleRuleOnEdit($id, $data, $model)
	{
	    throw new \Exception(\Yii::t('app', "Erro ao atualizar. O registro ". array_values($id)[0]. " não foi encontrado"));
	}
	
	private function _saveMultipleAndDelete($dados, $model=null, $relacao=null, $colunaDelete, $scenarioModelFilho = null)
	{
		try{

			preg_match('/.*(?<=\\\\)/si', get_class($this), $match);

			if (empty($match[0])) {
				return false;
			}
	
			$namespace = $match[0];
	
			if(!empty($relacao)) {
				$modelRelacao = $namespace . $relacao[0];
				$idRelacao = $relacao[1];
				$valorRelacao = $relacao[2];
				
			} else {
				$modelRelacao = $namespace . $model;
				$idRelacao = null;
				$valorRelacao = null;
				
			}
			
			$pkModel = $modelRelacao::primaryKey()[0];
			
			
			foreach($dados as $key => $col) {
			
				// related
				if($idRelacao)
					$col[$idRelacao] = $valorRelacao;

				// delete
				if (!empty($col[$colunaDelete])) {
					if (!empty($col[$pkModel])) {
						$m = $modelRelacao::findOne($col[$pkModel]);
						$m->delete();
					}
					continue;
				}
				
				// save or update
				if (empty($col[$pkModel])) {
					$m = new $modelRelacao();
					unset($col[$pkModel]);	
				} else 
					$m = $modelRelacao::findOne($col[$pkModel]);
					
				$m->setAttributes($col);
				
				if (!is_null($scenarioModelFilho)) {
				    $m->setScenario($scenarioModelFilho);
				}
				
				$m->save();
				
			}
		
        } catch (\Exception $e) {
            throw $e;
        }
		
	}
	
    private function _saveRelated($dados, $relacao, $flgAtivo, $scenarioModelFilho = null)
    {
        try {
            
			/*----------- Caso esteja usando o dhtmlxForm.sendData() no form abaixo obtenho o id que ele envia automaticamente ----------*/
            $id = $this->primaryKey()[0];
            if (isset($dados[0]['id'])) {
                $dados[0][$id] = $dados[0]['id'];
            }
			/*---------------------------------------------------------------------------------------------------------------------------------------------------*/
			
		    /*----------- Verifica se deve atualizar o registro ao invés de cria-lo-------------------*/
			$modelPai = $this;
			if(!empty($dados[0][$id])){
				$modelPai = $this->findOne($dados[0][$id]);
			}
			/*----------------------------------------------------------------------------------------------*/
			
            $modelPai->setAttributes($dados[0]);
            
			if ($modelPai->save()) {
			
				/*----------- Salva o modelo filho ----------*/
                $idValor = $modelPai->{$id};
				$this->_saveMultiple($dados[1], null, [array_keys($relacao)[0],array_values($relacao)[0],$idValor], $flgAtivo, $scenarioModelFilho);
				/*------------------------------------------------*/	
			
            }
			
        } catch (\Exception $e) {
            throw $e;
        }

    }
	
    private function saveRelatedAndMultiple($tipo, $dados, $model, $relacao, $flgAtivo, $transacao, $scenarioModelFilho = null)
	{
	
		try{
		
			if($transacao) {
				$connection = \Yii::$app->db;
				$transaction = $connection->beginTransaction();
			}
			
			if($tipo==1) {
				$this->_saveRelated($dados, $relacao, $flgAtivo, $scenarioModelFilho);
				
			} elseif($tipo==2) { 
				$this->_saveMultiple($dados, $model, null, $flgAtivo, $scenarioModelFilho);
				
			} elseif($tipo==3) { 
				$this->_saveMultipleAndDelete($dados, $model, null, $flgAtivo, $scenarioModelFilho);
				
			}
			
			if($transacao) {
				$transaction->commit();
			}
			return true;
			
        } catch (\Exception $e) {
			if($transacao) {
				$transaction->rollBack();
			}
            throw $e;
        }
		
	}
	
    /**
	* saveRelated
	* Salva em duas tabelas relacionadas "pai e filho(s)"
	*
	* @access Public
	* @author Eduardo M. Pereira
	* @package GlobalModel
	* @since 12/2016
	* @param Array $dados
	* 		$dados[0] = dados do pai
	* 		$dados[1] = dados do(s) filho(s)
	* 		Ex:
	* 		$dados = [
	* 		        0 => ['LISTA' => 'BLA', 'DESCRICAO_LISTA' =>'BLA'],
	* 		        1 =>[
	* 		             ['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA'],
	* 		             ['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA'],
	* 		            ],
	* 		         ];
	* @param Array $relacao
	* 		[key]: Nome do model filho(funciona apenas se o model do filho estiver no mesmo diretorio do pai),
	* 		[value]: Nome do campo(FK) que se relaciona com o pai
	* 		Ex: $relacao = ['filhoModel' => 'ID_PAI'];
	* @param String $flgAtivo = nome do campo flg ativo do modelo
	* @param Boolean $transacao
	* @return true|Exception
	*/
    public function saveRelated($dados, $relacao, $flgAtivo = true, $transacao = true, $scenarioModelFilho = null)
	{
		return $this->saveRelatedAndMultiple(1, $dados, null, $relacao, $flgAtivo, $transacao, $scenarioModelFilho);
	}
	
    /**
	* saveMultiple
	* Salva (insert or update) ou Deleta registros de uma tabela
	*
	* @access Public
	* @author Eduardo M. Pereira
	* @package GlobalModel
	* @since 12/2016
	* @param Array $dados
	* 		$dados[0] = dados do pai
	* 		$dados[1] = dados do(s) filho(s)
	* 		Ex: $dados = [['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA'], ['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA']];
	* @param String $model = Nome do modelo para salvar os dados
	* @param String $flgAtivo = nome do campo flg ativo do modelo
	* @param Boolean $transacao
	* @return true|Exception
	*/
   public function saveMultiple($dados, $model, $flgAtivo = true, $transacao = true)
	{
		return $this->saveRelatedAndMultiple(2, $dados, $model, null, $flgAtivo, $transacao);
	}
	
    /**
	* saveAndDeleteMultiple
	* Salva (insert or update) ou Deleta registros de uma tabela
	*
	* @access Public
	* @author Eduardo M. Pereira
	* @package GlobalModel
	* @since 01/2017
	* @param Array $dados
	* 		Ex: 
	*			$dados = [
	*				['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA', 'EXCLUIR'=>0], 
    *				['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA', 'EXCLUIR'=>0],
    *				['ITEM_LISTA' => 'BLA', 'DESC_ITEM' =>'BLA', 'EXCLUIR'=>1], // este registro será excluído
	*			];
	* @param String $model = Nome do modelo para salvar os dados
	* @param String $colunaDelete = chave que controla a exclusao do registro, se "1" exclui (DELETE) o registro
	* @param Boolean $transacao
	* @return true|Exception
	*/
   public function saveAndDeleteMultiple($dados, $model, $colunaDelete, $transacao = true)
	{
		return $this->saveRelatedAndMultiple(3, $dados, $model, null, $colunaDelete, $transacao);
	}
	
	/**
	 * findTable
	* retorna dados de uma tabela
	*
	* @access Public
	* @author Eduardo M. Pereira
	* @package GlobalModel
	* @since 01/2016
	* @param String $table
	* @param String $where
	* @param String $orderBy
	* @return Array
	*/
	public static function findTable($table, $where, $orderBy = null)
	{
	    $where = ($where) ? "WHERE " . $where : "";
	    $orderBy = ($orderBy) ? "ORDER BY " . $orderBy : "";
	
	    $query = "SELECT * FROM $table $where $orderBy";
	
	    $connection = \Yii::$app->db;
	    $command = $connection->createCommand($query)->query();
	    return $command->readAll();
	}
	
	/**
	 * Converts the input value according to [[phpType]] after retrieval from the database.
	 * If the value is null or an [[Expression]], it will not be converted.
	 * @param mixed $value input value
	 * @return mixed converted value
	 * @since 2.0.3
	 */
	protected function typecast($value)
	{
	    if ($value === '' && $this->type !== Schema::TYPE_TEXT && $this->type !== Schema::TYPE_STRING && $this->type !== Schema::TYPE_BINARY) {
	        return null;
	    }
	    if ($value === null || gettype($value) === $this->phpType || $value instanceof Expression) {
	        return $value;
	    }
	    switch ($this->phpType) {
	        case 'resource':
	        case 'string':
	            if (is_resource($value)) {
	                return $value;
	            }
	            if (is_float($value)) {
	                // ensure type cast always has . as decimal separator in all locales
	                return str_replace(',', '.', (string) $value);
	            }
	            if ($value == 'OCI-Lob') {
	                return $value;
	            }
	            return $value;
	        case 'integer':
	            return (int) $value;
	        case 'boolean':
	            // treating a 0 bit value as false too
	            // https://github.com/yiisoft/yii2/issues/9006
	            return (bool) $value && $value !== "\0";
	        case 'double':
	            return (double) $value;
	    }
	
	    return $value;
	}
	
	/**
	 * globalDxDateToMMSDate
	 * Formata data de um campo dhtmlxCalendar para o padrão d/m/Y
	 * 
	 * @param $campoData nome do atributo
	 * @return void
	 * @since 08/2017
	 */
	public function globalDxDateToMMSDate($campoData)
	{   
	  $this->$campoData = date('d/m/Y',strtotime(str_ireplace(' (Hora oficial do Brasil)', '', $this->$campoData)));
	}
	
	/**
	 * ucFirstMMS
	 * Coloca a primeria letra da string maiuscula
	 *
	 * @param $campoData nome do atributo
	 * @return void
	 * @since 08/2017
	 */
	public function ucFirstMMS($campoData)
	{
	    $this->$campoData = ucfirst(strtolower($this->$campoData));
	}	
}
