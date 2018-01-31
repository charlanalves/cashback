<?php
namespace common\components;

	use Yii;
	use yii\base\Component;

	
	/**
	* dataDumpComponent
	* Classe responsavel por retornar xml
	*
	* @access Public
	* @author Vitor Hallais
	* @package Component
	* @since  05/2016
	* @example Seguranca/usuario/xmldados
	*
	**/
	class dataDumpComponent extends Component
	{	 
	    /*
	     * getXML
	     * Retorna o xml do grid no padrão DHTMLX
	     *
	     * @autor Vitor Hallais
	     *
	     * @param array $dados - dados oriundos da query 
	     * @param array $config - array chave valor contendo o cabeçalho do grid
	     * @param function|null $function - função que será executada antes da criação 
	     * do xml da linha - geralmente utilizada para estilizar as linhas do grid
	     *
	     * @return string xml - xml do grid no padrão DHTMLX
	     *
	     */
		public function getXML($dados=null, $config=null, $function=null)
		{		
			$attachHeader = null;
			$afterInitXml = null;
			$beforeInit = null;
			$beforeInitXml = null;
			$numColuna = array();
			$ordem = array();
			$novo = array();
			$cont = 0;
			$xml  = "<?xml version='1.0' encoding='utf-8' ?>\n";
			$xml .= "<rows>\n";
			
			if (is_array($config)) {
				if (!array_key_exists('imagem',$config)) $config['imagem'] = null;
				foreach($config as $k=>$cabecalho) {
					if ($k == 'header') {
						$xml .= "<head>\n";
						foreach($cabecalho as $k2=>$colunas) {
							foreach($colunas as $k3=>$coluna) {
								if ($k2 == 0) { // Primeira linha do Cabecalho
									$valor_coluna = '';
									$xml .= "		<column style='border-right: 1px solid #A00' ";
									foreach($coluna as $param=>$col) {
										if ($param == 'title') {
											$valor_coluna .= $col;
										} else if (is_array($col)) {
											foreach($col as $k=>$v) {
												$valor_coluna .= "<".$param;
												foreach($v as $propriedade=>$val_prop) {
													if ($propriedade != 'text') {
														$valor_coluna .= " $propriedade='$val_prop'";
													} else {
														$valorOption = $val_prop;
													}
												}
												$valor_coluna .= ">".$valorOption."</".$param.">";
											}
										} else {
											$xml .= $param."='".$col."' ";
											// verifica e guarda o numero da coluna caso a coluna seja de imagem (tratamento diferenciado para imprimir a imagem)
											if ($param == 'type' and $col == 'img') {
												$numColuna[] = $cont;
											}

											if ($param == 'id') {
												$ordem[] = $col;
											}
										}
									}
									$xml .= ">".$valor_coluna."</column>";
									$cont++;
									unset($valor_coluna,$valorOption);
								// BeforeInit
								} elseif(isset($coluna['command'])) {
									$beforeInit[] = $coluna;
								// AfterInit
								} else {
									foreach($coluna as $param=>$col) {
										$attachHeader[$k2][] = $col;
									}
								}
							}
						}
						// Definições depois de inicializar a grid
						if (is_array($attachHeader)) {
							$afterInitXml .= "<afterInit>";
							foreach($attachHeader as $colunas) {
								$inicioAttach = false;
								$afterInitXml .= '   <call command="attachHeader"> <param>';
								foreach($colunas as $col) {
									if ($inicioAttach) $separador = ",";
									else {
										$separador = "";
										$inicioAttach = true;
									}
									$afterInitXml .= $separador.$col;
								}
								$afterInitXml .= "</param></call>";
							}
							
							//Altera a altrura da row mantendo a compatiblidade com a função enableSmartRendering
							$afterInitXml .= '<call command="setAwaitedRowHeight"> <param>20</param></call>';
							//Ativa o smartRender
							$afterInitXml .= '<call command="enableSmartRendering"> <param>true</param></call>';
							
							if (!empty($config['afterInit']) && is_array($config['afterInit'])){
							    $afterInitXml .= implode('\n', $config['afterInit']);
							}
							$afterInitXml .= "</afterInit>\n";
							$xml .= $afterInitXml;
						}
						// Definições antes de inicializar a grid
						if (is_array($beforeInit)) {
							$beforeInitXml .= "<beforeInit>";
							foreach($beforeInit as $beforeItem) {
								$beforeInitXml .= '<call command="'.$beforeItem['command'].'"> <param>';
								foreach($beforeItem['param'] as $beforeItemParam) {
									$beforeInitXml .= $beforeItemParam;
								}
								$beforeInitXml .= "</param></call>";
							}
							$beforeInitXml .= "</beforeInit>\n";
							$xml .= $beforeInitXml;
						}
						
						$xml .= "</head>\n";
					}
				}
			}
			// varre os array de dados para imprimir o xml na forma correta
			if (is_array($dados)) {	
			   if(is_null($function)){
    		       return $this->_getXmlGrid($dados, $ordem, $xml, $function);			       
			   } else {
			       return $this->_getXmlDeprecated($xml, $dados, $ordem, $function, $numColuna, $config);
			   }
			}
			
		}
	
		 /*
		 * _getXmlGrid
		 * Realiza ações necessáras afim de obter o xml do grid tais como :
		 *  - Definir o id da linha
		 *  - Ordenar os dados baseado no cabeçalho do grid
		 *  - Evento que executado antes do xml da linha ser construida
		 * 
		 *
		 * @autor Charlan Santos
		 * @since 12/09
		 *
		 * @param string $dados
		 * @param string $ordem
		 * @param string $xml
		 * @param string $function
		 *
		 * @return string xml - xml do grid no padrão DHTMLX
		 *
		 */
	   private function _getXmlGrid($dados, $ordem, &$xml, $function)
	   {
	       $cellAtributes = '';
	       foreach($dados as $k => $v) {
	           $linha = '';
	           $xml .= "<row ";
	           $contCols = 0; // zera o contador de colunas a cada nova linha de registros
	           $novo = '';
	           $idLinha = null;
	            
	           $this->defineIdLinha($v, $xml, $idLinha);
	           $this->ordenaDados($ordem, $novo, $v);	         
	           $this->criarXmlLinhaGrid($cellAtributes, $novo, $xml);
	       }
	   
	       $xml .= "</rows>";
	   
	       return $xml;
	   }
	   
	   /*
	    * defineIdLinha
	    * Define o id da linha que por padrão será o campo com alias 'ID' da query
	    *
	    * @autor Charlan Santos
	    * @since 12/09
	    *
	    * @param array &$v - array com os dados da linha sendo que cada posição é uma coluna
	    * @param string &$xml - Xml com somente com as tags de abertura
	    * @param string &$idLinha - id da linha	    
	    *
	    */
	   private function defineIdLinha(&$v, &$xml, &$idLinha)
	   {
	       if (isset($v['ID'])) {
	           $idLinha = $v['ID'];
	           $xml .= "id='".$v['ID']."'";
	           unset($v['ID']);
	       }
	   }
	  
	  
	   /*
	    * ordenaDados
	    * Ordena os dados de acordo com o cabeçalho
	    *
	    * @autor Charlan Santos
	    * @since 12/09
	    *
	    * @param string &$ordem 
	    * @param string &$novo - variavel que receberá as linhas ordenadas
	    * @param array &$v - array com os dados da linha sendo que cada posição é uma coluna
	    * 	  
	    * @return $dados - Ordenados por referência
	    *
	    */
	   private function ordenaDados(&$ordem, &$novo, &$v)
	   {
	       if (!empty($ordem)) {
	           foreach ($ordem as $o) {
	               $novo[$o] = $v[$o];
	           }
	       } else {
	           $novo = $v;
	       }
	   }
	   
	   /*
	    * criarXmlLinhaGrid
	    * Retorna o cabeçalho do grid para ser usado no método dataDumpComponent::getXML()
	    *
	    * @autor Charlan Santos
	    *
	    * @param string $cellAtributes - Atributos que serão inseridos na celula 
	    * @param array $linhas - array com os dados de cada coluna
	    * @param string &$xml - string que recebera o xml por referência	   
	    *
	    * @return string xml - XML com os dados
	    *
	    */
	   private function criarXmlLinhaGrid($cellAtributes, $linhas, &$xml)
	   {
	       // Cria o xml dos dados todos com CDATA
	       $xml .= ">";
	       
	       $xmlAux = '';
	        
	       $xmlAux .= "<cell $cellAtributes ><![CDATA[";
	       $xmlAux .= implode("]]></cell>\n<cell><![CDATA[",array_values($linhas));
	       $xmlAux .= "]]></cell>\n";
	        
	       // Removendo o último \n
	       $xmlAux = explode("\n", $xmlAux);
	       array_pop($xmlAux);
	        
	       $xml .= implode("\n", $xmlAux);
	        
	       $xml .= "</row>\n";
	   }

		public function getXmlCombo($data = [], $emptyText = 'Selecione...', $selecionado='')
		{
			if (empty($data)) {
				$data = [];
			}
			
			$options = '';

			$rootTag = 'complete';

			$startXml = '<?xml version="1.0" encoding="utf-8"?>';
			$bodyXml = "<$rootTag>";

			if (!empty($data)) {
				if ($emptyText !== false) {
					$options = '<option value="" selected="true">'.Yii::t("app", $emptyText).'</option>';
				}

				foreach ($data as $id => $text) {
					if($id==$selecionado)
					  $options .= '<option value="'.$id.'" selected="1">'.$text.'</option>';
					else
					  $options .= '<option value="'.$id.'"><![CDATA['.$text.']]></option>';
				}
			}

			$bodyXml .= $options;
			$endXml = "</$rootTag>";

			$xml = $startXml . $bodyXml . $endXml;

			return $xml;
		}
		
		private function _getXmlDeprecated($xml, $dados, $ordem, $function, $numColuna, $config)
		{   
		        foreach($dados as $k0 => $v) {
		            $linha = '';
		            $xml .= "<row ";
		            $contCols = 0; // zera o contador de colunas a cada nova linha de registros
		    
		            if (!empty($ordem)) {
		                foreach ($ordem as $o) {
		                    $novo[$o] = $v[$o];
		                }
		                if (isset($v['ID'])) $novo['ID'] = $v['ID'];
		            } else {
		                $novo = $v;
		            }
		    
		    
		            foreach($novo as $k=> $valores) {
		                 
		                // seta o ID da row caso seja passado
		                if ($k === 'ID'){ $xml .= "id='".$valores."'"; $id_current = $valores; }
		                // verifica se a coluna Ã© do tipo imagem para dar tratamento diferenciado.
		                else if (in_array($contCols,$numColuna)) $linha .= "<cell>".$valores."</cell>\n";
		                // outro modo de setar o campo como imagem para ter tratamento diferenciado
		                // -> usado normalmente quando nao se tem header no XML e precisa definir o campo como imagem.
		                else if (is_array($config['imagem']) and in_array($k,$config['imagem'])) $linha .= "<cell>".$valores."</cell>\n";
		                // todos os outros tipos serÃ£o encapsolados por CDATA
		                else {
		                    $id_linha = isset($novo['ID']) ? $novo['ID'] : null;
		                    $cellAtributes = (is_callable($function)) ?	call_user_func($function, $id_linha, $k, $contCols, $valores) : "";
		                    $linha .= "<cell ".$cellAtributes."><![CDATA[".$valores."]]></cell>\n";
		                }
		                $contCols++;
		            }
		            $xml .= ">".$linha."</row>\n";
		        }
		    
		    $xml .= "</rows>";
		    return $xml;
		}
		
		public function getXmlComboAninhado($data = [], $emptyText = 'Selecione...', $selecionado='')
		{
			if (empty($data)) {
				$data = [];
			}
		
			$options = '';
		
			$rootTag = 'complete';
		
			$startXml = '<?xml version="1.0" encoding="utf-8"?>';
			$bodyXml = "<$rootTag>";
		
			if (!empty($data)) {
				if ($emptyText !== false) {
					$options = '<option value="" selected="true">'.Yii::t("app", $emptyText).'</option>';
				}else{
					$options = '<option value="">'.Yii::t("app", "Selecione...").'</option>';
				}
		
				foreach ($data as $id => $text) {
					if($id==$selecionado)
						$options .= '<option value="'.$id.'" selected="1">'.$text.'</option>';
						else
							$options .= '<option value="'.$id.'"><![CDATA['.$text.']]></option>';
				}
			}
		
			$bodyXml .= $options;
			$endXml = "</$rootTag>";
		
			$xml = $startXml . $bodyXml . $endXml;
		
			return $xml;
		}
		
		/**
		 *  getXmlTreeview
		 *  Recebe array e retorna xml no formato para treeview do dhtmlx
		 *
		 *  @author Vitor Hallais
		 *  @return string
		 *
		 */
		public function getXmlTreeview($data = [])
		{
			if (empty($data)) {
				$data = [];
			}

			$options = '';
			$temAnterior = false;

			$startXml = '<?xml version="1.0" encoding="utf-8"?>';
			$bodyXml = '<tree id="0">';

			if (!empty($data))
				$options .= Yii::$app->dataDumpComponent->xmlRecursivoTreeView($data);

			$bodyXml .= $options;
			$endXml = "</tree>";

			$xml = $startXml . $bodyXml . $endXml;

			return $xml;
		}

		/**
		 *  xmlRecursivoTreeView
		 *  metodo recursivo para montar a estrutura do xml da treeview
		 *
		 *  @author Vitor Hallais
		 *  @return string
		 *
		 */
		public function xmlRecursivoTreeView($dadosTree)
		{
			if (!is_array($dadosTree)) return false;
			$options = '';
			foreach($dadosTree as $k=>$v) {
				$options .= '<item ';
				if (is_array($v)) {
					foreach($v as $k2=>$v2) {
						if ($k2 != 'opcoes') {
							$options .= $k2.'="'.$v2.'" ';
						}
					}
					if (array_key_exists('opcoes',$v) and is_array($v['opcoes'])) {
						$options .= '>';
						$options .= Yii::$app->dataDumpComponent->xmlRecursivoTreeView($v['opcoes']);
						$options .= '</item>';
					} else {
						$options .= '/>';
					}
				} else {
					$options .= '/>';
				}
			}
			return $options;
		}
	}
	?>
