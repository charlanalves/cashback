<?php

namespace app\components;

use yii\base\Component;

/**
 * ExportaExcelComponent
 * Componente responsavel por emitir relatorio em excel
 *
 * @access Public
 * @author Vitor Hallais
 * @package Component
 * @since  07/2016
 *
 **/

/*
 COMO UTILIZAR NO CONTROLLER:

 1- Defina o nome do arquivo do relatório
	$filename = "relatorio.xls";
 
 2- Monte o vetor do cabeçalho
	 $config[] = array( 
	 	array('value'=>Yii::t("app",'COLUNA 1')),
	 	array('value'=>Yii::t("app",'COLUNA 2')),
		array('value'=>Yii::t("app",'COLUNA 3')),
	 );
 
	 $config[] = array( 
	 	array( 'colspan'=>2, 'value'=>Yii::t("app",'BAIXO 1') ),
	 	array( 'value'=>Yii::t("app",'BAIXO 3') )
	 );
 
 3- Monte o vetor de dados
	 $dados[] = array( 
	 	array('value' => $dados['COLUNA1'], 'style'=>"background-color: red;"),
	 	array('value' => $dados['COLUNA2']),
	 	array('value' => $dados['COLUNA3']),
	 );

 4 - Defina o texto para colocar antes da tabela de dados
 	$info_header = 'Texto a ser adicionado antes da tabela';
 
 5 - Defina o texto para colocar depois da tabela de dados
	$info_footer = 'Texto a ser adicionado depois da tabela';

 6 - Junte os dados em um único vetor
 	$estrutura = array(
 		'config'      => $config,
 		'dados'       => $dados,
 		'info_header' => $info_header,
 		'info_footer' => $info_footer
 	);
 	
 7 - Chame o método do componente para exportar o excel
	Yii::$app->ExportaExcelComponent->exportaExcel($filename, $estrutura);
	
 */

class ExportaExcelComponent extends Component
{
	public function exportaExcel($filename, $estrutura=null)
	{
		if (empty($filename)) {
			$filename = 'relatorio.xls';
		}
		
		header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-Disposition: attachment; filename=".$filename);

		if (is_array($estrutura)) {
			foreach($estrutura as $variavel=>$valor) {
				${$variavel} = $valor;
			}
		}

		if (!isset($config))      $config = NULL;
		if (!isset($dados))       $dados = NULL;
		if (!isset($info_header)) $info_header = NULL;
		if (!isset($info_footer)) $info_footer = NULL;
		
		// inicia variaveis para percorrer o cabecalho
		$table_content = '';
		$numCell_anterior = NULL;
		$aux = NULL;
		$aux_col = NULL;
		$valor = NULL;
		$conteudo_linha = NULL;
		
		if (is_array($config)) {
			
			$table_content .= "<thead>";
			
			foreach($config as $linha=>$colunas) {
				foreach($colunas as $numCell=>$dcoluna) {
					if ($numCell != $numCell_anterior and !is_null($numCell_anterior)) {
						$conteudo_linha .= "<th ".$aux.">".$valor."</th>"; // monta a linha
						$aux = $valor = '';
					}
					foreach($dcoluna as $k=>$valores) {
						if ($k != 'value') $aux .= $k.'="'.$valores.'" '; // complementos da coluna do cabecalho
						else $valor = $valores; // valor da coluna
					}
					$numCell_anterior = $numCell;
				}
				if (!is_null($numCell_anterior)) {
					$conteudo_linha .= "<th ".$aux.">".$valor."</th>";  // valor da ultima coluna do cabecalho
				}
				$table_content.= "<tr ".$aux_col.">".$conteudo_linha."</tr>"; // incrementa a linha por inteiro
				$conteudo_linha = '';
				$numCell_anterior = $aux = $aux_col = NULL;
			}
			
			$table_content .= "</thead>";
		}

		// inicia variaveis para varrer dados
		$numCell_anterior = NULL;
		$aux = NULL;
		$aux_col = NULL;
		$valor = NULL;
		$conteudo_linha = NULL;
		
		if (is_array($dados)) {
			
			$table_content .= "<tbody>";
			
			foreach($dados as $linha=>$colunas) {
				foreach($colunas as $numCell=>$dcoluna) {
					if ($numCell != $numCell_anterior and !is_null($numCell_anterior)) {
						$conteudo_linha .= "<td ".$aux.">".$valor."</td>"; // monta a linha
						$aux = $valor = '';
					}
					foreach($dcoluna as $k=>$valores) {
						if ($k != 'value') $aux .= $k.'="'.$valores.'" '; // complementos da coluna do cabecalho
						else $valor = $valores; // valor da coluna
					}
					$numCell_anterior = $numCell;
				}
				if (!is_null($numCell_anterior)) {
					$conteudo_linha .= "<td ".$aux.">".$valor."</td>";  // valor da ultima coluna do cabecalho
				}
				$table_content.= "<tr ".$aux_col.">".$conteudo_linha."</tr>"; // incrementa a linha por inteiro
				$conteudo_linha = '';
				$numCell_anterior = $aux = $aux_col = NULL;
			}
			
			$table_content .= "</tbody>";
		}

		// monta toda a pagina
		$excel = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
                  	<head><meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\"/></head>
                	<body>
                		$info_header
                		<table border='1' width='100%'>
                			$table_content
                		</table>
                		$info_footer
                 	</body>
				 </html>";
		
		echo $excel;
	}
}
?>