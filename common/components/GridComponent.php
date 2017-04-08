<?php
/**
* GridComponent
* Classe responsavel por criar grid
*
* @access Public
* @author Vitor Hallais
* @package Component
* @since  05/2016
*
**/


class GridComponent
{

    public function iniciaGrid($param=array()) 
    {
		$indices = array('titulo','botoes');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}	
		$acao = null;
        $titulo = $param['titulo'];
        $botoesHeader = $param['botoes'];
        
        $retorno = "VARIAVEIS ={
                        Windows: {
                            AddJanela:{}
                        }
                    }

                    SYSTEM.boot();

                    SYSTEM.Layout.tela.hideHeader();
                    SYSTEM.Layout.tela.collapse();
                    SYSTEM.Toolbar.titulo('".Yii::t("app", $titulo)."');
        ";
        if (is_array($botoesHeader)) {
            $botaoInicio = "SYSTEM.Toolbar.core.attachEvent('onClick', function(itemId) {";
            $botaoFim = "});";
            foreach($botoesHeader as $nomeAcao=>$btnAcao) {
                $retorno .= " SYSTEM.Toolbar.icones(['".$nomeAcao."']);";
                if ($acao) $acao.= " else ";
                $acao .= "if (itemId == '".$nomeAcao."') { ".$btnAcao." } ";
            }
            $retorno .= $botaoInicio.$acao.$botaoFim;
        }
        return $retorno;
        
    }
    
    public function carregaGridXML($param=array()) 
    {
		$indices = array('carregaGrid','tituloListagem');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}	
        $action = $param['carregaGrid'];
        $tituloListagem = $param['tituloListagem'];
        
        $retorno = "
            preencheGrid();

            function preencheGrid()
            {
                SYSTEM.Layout.t1('".Yii::t("app", $tituloListagem)."');
                SYSTEM.Grid.init();
                SYSTEM.Grid.loadXML('".$action."');
            }
            
            function reloadGrid(msg)
            {
				SYSTEM.Grid.clearAll();
				SYSTEM.Grid.loadXML('".$action."');
				if (msg) {
					dhtmlx.alert({
						text: msg,
						ok:'Ok'
					});
				}
            }
        ";
        return $retorno;
    }
    
    public function carregaGridJson($param=array()) 
    {
		$indices = array('carregaGrid');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}	
        $action = $param['carregaGrid'];
        $retorno = "
            preencheGrid();

            function preencheGrid()
            {
                $.ajax({
                    url: '".$action."',
                    data: {},
                    type: 'GET',
                    success: function(data){
                        console.log(data);
                        SYSTEM.Layout.t1('".Yii::t("app", "Listagem")."');
                        SYSTEM.Grid.clearAll();                        
                        // CABECALHO
                        for(i = 0; i < data.head.length; i++) {
                            if (i == 0) SYSTEM.Grid.setHeader(data.head[i][0]);
                            else SYSTEM.Grid.attachHeader(data.head[i][0]);
                        }
                        // PROPRIEDADES DO CABECALHO
                        if (data.width) SYSTEM.Grid.setInitWidths(data.width[0]);
                        if (data.align) SYSTEM.Grid.setColAlign(data.align[0]);
                        if (data.types) SYSTEM.Grid.setColTypes(data.types[0]);
                        if (data.sort) SYSTEM.Grid.setColSorting(data.sort[0]);
                        if (data.date) SYSTEM.Grid.setDateFormat(data.date[0]);
                        
                        
                        SYSTEM.Grid.init();
                        // FILTROS
                        if (data.filtros) SYSTEM.Grid.attachHeader(data.filtros[0]);

                        SYSTEM.Grid.parse(data,'json');
                        
                    },
                    error: function(xhr, textStatus, error){
                        dhtmlx.alert({
                            title:'Erro',
                            type:'alert-error',
                            text:'Erro: '+error
                        });
                    }
                });
            }
            
            function reloadGrid(msg)
            {
                $.ajax({
                    url: '".$action."',
                    data: {},
                    type: 'GET',
                    success: function(data){
                        if (msg) {
                            dhtmlx.alert({
                                text: msg,
                                ok:'Ok'
                            });
                        }
                        SYSTEM.Grid.clearAll();
                        SYSTEM.Grid.parse(data);
                    },
                    error: function(xhr, textStatus, error){
                        dhtmlx.alert({
                            title:'Erro',
                            type:'alert-error',
                            text:'Erro: '+error
                        });
                    }
                });
            }
        ";
        return $retorno;
    }    
    
    public function apagaRegistro($param=array())
    {
		$indices = array('apagaRegistro','acaoApagar','textoConfirmaExclusao');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}	
        $action = $param['apagaRegistro'];
        $acaoSucesso = $param['acaoApagar'];
		$textoConfirmaExclusao = ($param['textoConfirmaExclusao']?$param['textoConfirmaExclusao']:'Confirma excluir este registro?');
        
        $retorno = "
            function excluirRegistro(id)
            {
                dhtmlx.confirm({
                    type:'confirm',
                    text:'".$textoConfirmaExclusao."',
                    ok:'Sim', 
                    cancel:'Não',
                    callback: function(result){
                       if (result == true) 
                        {
                            $.ajax({
                                url: '".$action."',
                                type: 'POST',
                                data: { 
									'id': id , 
									'".Yii::$app->request->csrfParam."': '".Yii::$app->request->csrfToken."' 
								},           
                                success: function(msg){
                                    ".($acaoSucesso?$acaoSucesso:"")."
                                },
                                error: function(xhr, textStatus, error){
                                    dhtmlx.alert({
                                        title:'Erro',
                                        type:'alert-error',
                                        text:'Erro: '+'Não foi possivel excluir o registro'
                                    });
                                }
                            });
                        }
                    }
                });
            }
        ";
        return $retorno;
    }
    
    public function addRegistro($param=array())
    {
		$indices = array('criaForm','salvaForm','campos','acaoAdd','complemento','formwidth','formheight','sem_redimensionamento','sem_minmax','sem_park');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}
        $actionForm = $param['criaForm'];
        $actionGravar = $param['salvaForm'];
        $campos = $param['campos'];
        $acaoSucesso = $param['acaoAdd'];
        $complemento = $param['complemento'];
        $formWidth = $param['formwidth'];
        $formHeight = $param['formheight'];
		$sem_redimensionamento = $param['sem_redimensionamento'];
		$sem_minmax = $param['sem_minmax'];
		$sem_park = $param['sem_park'];
        
        if (!$formWidth) $formWidth = 500;
        if (!$formHeight) $formHeight = 500;
        $i = 0;
		$dataCampos='';
        if (is_array($campos)) {
            $qtdeCampos = count($campos);
            foreach($campos as $c) {
                $i++;
                if ($i == $qtdeCampos) $separador = false;
                else $separador = true;
                $dataCampos .= "'".$c."': VARIAVEIS.Windows.AddJanela.Form.getItemValue('".$c."')".($separador?",":"");
            }
        }
        
        $retorno = "
            function WindowAdicionar(VARIAVEIS)
            {
                VARIAVEIS.Windows.AddJanela.Janela = new dhtmlXWindows();
                VARIAVEIS.Windows.AddJanela.Janela.createWindow('AddJanela', 0,0, ".$formWidth.", ".$formHeight.");
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').setText('Adicionar novo registro');
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').center();
				".($sem_redimensionamento?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').denyResize();":"")."
				".($sem_minmax?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').button('minmax1').hide();":"")."
				".($sem_park?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').button('park').hide();":"")."
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').setModal(true);
                $.ajax({
                    url: '".$actionForm."',
                    success: function(resposta){
                        VARIAVEIS.Windows.AddJanela.Form = VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').attachForm();
                        VARIAVEIS.Windows.AddJanela.Form.loadStruct([resposta],'json');
                        VARIAVEIS.Windows.AddJanela.Form.setFocusOnFirstActive();
                        ".($param['complemento']?$param['complemento']:"")."
                        //valida o clic no botao cadastrar
                        VARIAVEIS.Windows.AddJanela.Form.attachEvent('onButtonClick', function(name) {
                            //seta o ajax de inserção
                            $.ajax({
                                url: '".$actionGravar."',
                                type: 'POST',
                                data: VARIAVEIS.Windows.AddJanela.Form.getFormData(),
                                success: function(msg){
                                    ".$acaoSucesso."
                                },
                                error: function(xhr, textStatus, error){
                                    dhtmlx.alert({
                                        title:'Erro',
                                        type:'alert-error',
                                        text:'Erro: '+error
                                    });
                                }
                            });
                        });
                        ".($complemento?$complemento:'')."
                    },
                    error: function() {
                        console.log('Erro chamada do formulario via ajax');
                    }
                });
            }
            function fecharJanela()
            {
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').close();
            }
        ";
        return $retorno;
        
    }
    
    public function editRegistro($param=array())
    {
		$indices = array('criaForm','salvaForm','campos','acaoEdit','complemento','formwidth','formheight','sem_redimensionamento','sem_minmax','sem_park');
		if (is_array($param)) {
			foreach($indices as $key) {
				if (!array_key_exists($key,$param)) $param[$key] = null;
			}
		}
        $actionForm = $param['criaForm'];
        $actionGravar = $param['salvaForm'];
        $campos = $param['campos'];
        $acaoSucesso = $param['acaoEdit'];
        $complemento = $param['complemento'];
        $formWidth = $param['formwidth'];
        $formHeight = $param['formheight'];
		$sem_redimensionamento = $param['sem_redimensionamento'];
		$sem_minmax = $param['sem_minmax'];
		$sem_park = $param['sem_park'];		
        
        if (!$formWidth) $formWidth = 500;
        if (!$formHeight) $formHeight = 500;		
        $i = 0;
        $dataCampos = "'id': id, ";
        if (is_array($campos)) {
            $qtdeCampos = count($campos);
            foreach($campos as $c) {
                $i++;
                if ($i == $qtdeCampos) $separador = false;
                else $separador = true;
                $dataCampos .= "'".$c."': VARIAVEIS.Windows.AddJanela.Form.getItemValue('".$c."')".($separador?",":"");
            }
        }    
        $retorno = "
            function WindowEditar(id)
            {
                VARIAVEIS.Windows.AddJanela.Janela = new dhtmlXWindows();
				VARIAVEIS.Windows.AddJanela.Janela.createWindow('AddJanela', 0,0, ".$formWidth.", ".$formHeight.");
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').setText('Editar');
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').center();
				".($sem_redimensionamento?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').denyResize();":"")."
				".($sem_minmax?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').button('minmax1').hide();":"")."
				".($sem_park?"VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').button('park').hide();":"")."				
                VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').setModal(true);
                $.ajax({
                    url: '".$actionForm."',
                    type: 'POST',
                    dataType: 'json',
                    data: { 'id' : id,'".Yii::$app->request->csrfParam."': '".Yii::$app->request->csrfToken."' },
                    error: function(xhr, textStatus, error){
                        alert('ERRO: '+error);
                    },
                    success: function(resposta){
                        var formulario = VARIAVEIS.Windows.AddJanela.Form = VARIAVEIS.Windows.AddJanela.Janela.window('AddJanela').attachForm();
                        VARIAVEIS.Windows.AddJanela.Form.loadStruct([resposta],'json');
                        VARIAVEIS.Windows.AddJanela.Form.setFocusOnFirstActive();
                        ".($param['complemento']?$param['complemento']:"")."
                        //valida o clic no botao cadastrar
                        VARIAVEIS.Windows.AddJanela.Form.attachEvent('onButtonClick', function(name) {
                            //seta o ajax de inserção
                            $.ajax({
                                url: '".$actionGravar."',
                                type: 'POST',
                                data: VARIAVEIS.Windows.AddJanela.Form.getFormData(),
                                success: function(msg){
                                    ".$acaoSucesso."
                                },
                                error: function(xhr, textStatus, error){
                                    dhtmlx.alert({
                                        title:'Erro',
                                        type:'alert-error',
                                        text:'Erro: '+error
                                    });
                                }
                            });
                        });
                        ".($complemento?$complemento:'')."
                    }
                });
            }
        ";
        return $retorno;
    }
}

?>