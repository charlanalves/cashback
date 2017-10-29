U = {};
// TODO: Alterar esse arquivo de .js para .php e carrega-lo num evento global do Yii 

/************************************************ M�TODOS DHTMLX FORM ***********************************************************************/
/*
 * Realiza a requisi��o ajax com jquery enviando os dados do form(getFormData)
 * inclusive com inputs files e dados extras
 *
 * @autor Charlan Santos
 *
 * @param object ajaxParams - Um objeto com as mesmas opcoes de um JqueryAjax convencional
 *
 * @return void
 */
dhtmlXForm.prototype.sendMMS = function(ajaxParams) {

	var inputFiles = document.querySelectorAll('input[type="file"]'),
		containers = this.getAllContainersMMS();	

	if (typeof Form == 'function') {
		Form.beforeSendMMS();
    } else if (typeof C7 == 'function'){
	    C7.beforeSendMMS();
    } else if (typeof M7 == 'function'){
        M7.beforeSendMMS();
    }
	
	ajaxParams.type = (ajaxParams.type || 'POST');	
	ajaxParams.data = (ajaxParams.data || {});	
	
		
	// Se tiver container com grid no form ser� automaticamente serializado 
	// e seus os dados e setados no objeto formData para ser enviado na requisi��o
	U.setContainerDataInsideFormData(containers, this);
		
	var data = this.getFormData();
	$.extend(ajaxParams.data, data);
	
	
	if ( inputFiles.length > 0 ) {
		// Se tiver input file no form o mesmo ser� preparado para ser enviado na requisi��o automaticamente
		var newAjaxParams = U.getInputFilesData(inputFiles, ajaxParams);
		
		$.extend(ajaxParams, newAjaxParams);
	}
		
	$.ajax(ajaxParams);
}
/*
 * Realiza a requisi��o ajax com jquery enviando os dados do form(getFormData)
 * inclusive com inputs files e dados extras
 *
 * @autor Charlan Santos
 *
 * @param string url - 
 * @param function callback - 
 *
 * @return void
 */
dhtmlXForm.prototype.loadJsonMMS = function(url, callback) {
	var ajaxParams = {};	
	ajaxParams.url = url;
	
	var that = this;
	
	ajaxParams.success = function(formData){
			try {
				that.loadStruct(formData, "json");
				if (typeof callback == 'function') {
					callback();
				}
			} catch(e) {
				// TODO: Alterar esse arquivo de .js para .php e carrega-lo num evento global do Yii 
				// Para resolver o problema da tradu��o das mensagens como abaixo por exemplo	
				dhtmlx.alert({
					title: "Aten��o!", 
					type: "alert-error errorCustom", 
					text: 'Erro ao carregar o form.',
					width: "100%", 
				});		
			}
	}
	
	this.sendMMS(ajaxParams);
}

/*
 * Obt�m os objetos dos components de todos containers do form
 * Para funcionar o name do container deve ser o mesmo do objeto js 
 * Ex: SYSTEM.GridFornecedores = new dhtmlxGridObject();
 *     {type:'container', name:'GridFornecedores'}
 * @autor Charlan Santos
 *
 * @return object Retorna um objeto com todos os componentes dos containers
 */
dhtmlXForm.prototype.getAllContainersMMS = function() {
var that = this;
var components = {};
this.forEachItem(function(name){
	var type = that.getItemType(name);

	if (type == 'container') {
		
		
		if (typeof Form == 'function') {
			if (typeof SYSTEM[name] != 'undefined') {
				components[name] = SYSTEM[name];
			}
			if (typeof Form[name] != 'undefined') {
				components[name] = Form[name];
			}
	    } else if (typeof C7 == 'function'){
	    	
			if (typeof C7[name] != 'undefined') {
				components[name] = C7[name];
			}
	    } else if (typeof M7 == 'function'){
			if (typeof M7[name] != 'undefined') {
				components[name] = M7[name];
			}
	    }
		
		
    }
});
return components;
}

dhtmlXForm.prototype.getClassNameMMS = function(){return 'dhtmlXForm';}

/*
 * add ao evento (onkeyup) no input informado uma mascara numerica
 * 
 * @autor Eduardo M. Pereira
 * @param inputName name input form
 * @param countDecimal || 0
 * @param delimiter || ","
 * @param eventName || "onkeyup" (onkeyup, onkeypress, onkeydown)
 */
dhtmlXForm.prototype.inputMaskNumberMMS = function(inputName, countDecimal, delimiter, eventName){
	if(typeof inputName !== 'undefined'){
		var	delimiter = (delimiter || ','),
				countDecimal = (countDecimal || " "),
				regex = new RegExp("(\\d{1})(\\d{1," + countDecimal + "})$"),
				eventName = (eventName || 'onkeyup'),
				imput = this.doWithItem(inputName, "getInput");
				imput[eventName] = function(e){
					imput.value = imput.value.replace(/\D/g,"").replace(regex, "$1" + delimiter + "$2");
				};
	}
}

dhtmlXForm.prototype.resizeWindowMMS = function(w){
	if (typeof w.setDimension !=  "undefined") {
		var width = document.querySelectorAll('.dhxform_base')[0].clientWidth + 30;
		var heights = [];
		$(".dhxform_base").each(function(k,v){ heights.push(this.clientHeight);})
		//var height = U.sort(heights, true)[1] + 45;
		var height = heights[1] + 50;
		w.setDimension(width, height);
		w.center();
	}
} 


/*
 * Executa o bind entre todos componentes autocomplete do form e o grid 
 * 
 * IMPORTANTE a coluna do grid deve estar com o sufixo _ACVALUE e _ACTEXT
 * EX: Para uma coluna com o nome MMS01_ID 
 * Deve se criar outra hidden com nome MMS01_ID_ACVALUE
 *   Para uma coluna com o nome MMS01_DESC 
 * Deve se criar outra hidden com nome MMS01_DESC_ACTEXT
 * 
 * @autor Charlan Santos
 * @param component Objeto DHTMLXGrid
 * @return void
 */
dhtmlXForm.prototype.bindACMMS = function(grid) {
	
    if (typeof grid.getClassNameMMS == 'undefined') {
		 console.error("O objeto informado para função bindACMMS não é um objeto DhtmlxGrid");
		 return;
    }
	 
    if (grid.getClassNameMMS() != 'dhtmlXGridObject'){
		console.error("O objeto informado para função bindACMMS não é um objeto DhtmlxGrid");
		 return;
	}
    
	that = this;
	success = true;
	
	this.forEachItem(function(name) {
	    var type = that.getItemType(name),
	   	   vSuffix = '_ACVALUE',
	   	   tSuffix = '_ACTEXT';

	    if (type === 'autocomplete'){  
		   try {
			   var vGridCel = grid.getCellTextSelected(name + vSuffix),
			   	   tGridCel = grid.getCellTextSelected(name + tSuffix);
			   
			    autocomplete = that.getCombo(name);
			    autocomplete.setComboText(tGridCel);
		        autocomplete.setComboValueMMS(vGridCel);
			}
			catch(err) {
				success = false;
			    console.warn("Você deve criar uma coluna no grid com o nome "+ name + vSuffix +" e outra com o nome "+ name + tSuffix +" para a função bindMMS funcionar corretamente");
			   
			}
	    }
	});
}

/*
 * Executa o bind entre form e grid  
 *
 * 
 * @autor Charlan Santos
 * @param grid Objeto DHTMLXGrid
 * @return void
 */
dhtmlXForm.prototype.bindMMSC = function(grid) {
	var selectedRow = grid.getRowIndex(grid.getSelectedRowId());
	
	this.bind(grid);
	
	grid.clearSelection();
	
	grid.selectRow(selectedRow);
}




/************************************************ FIM DOS M�TODOS DHTMLX FORM ***************************************************************/


/************************************************    MÉTODOS DHTMLX COMBO   *****************************************************************/

dhtmlXCombo.prototype.deleteFirstOptionMMS = function(className) {
		if ( typeof className != "undefined" ) {
			$('.'+ className +' input[type="checkbox"]:first-child').hide();
		}

    var b = 0;
    b < 0 || (this.optionsArr[b] == this._selOption && (this._selOption = null), this.optionsArr.splice(b, 1), this.redrawOptions())
} 

dhtmlXCombo.prototype.setComboValueMMS = function(a) {    
    for (var b = 0; b < this.optionsArr.length; b++)
        if (this.optionsArr[b].data()[0] == a) return this._skipFocus = !0, this.selectOption(b, null, !0);
    this.DOMelem_hidden_input.value = a
}

/************************************************    FIM DOS MÉTODOS DHTMLX COMBO   *****************************************************************/

/************************************************    M�TODOS DHTMLX GRID   *****************************************************************/






/*
 * Define um novo valor para a celula baseado nos ids da linha e coluna do grid
 * 
 * @autor Charlan Santos
 * @package dhtmlxGrid
 * @param string rowId - id da linha
 * @param string colId - id da coluna
 * @param string value - Um objeto com as mesmas opcoes de um JqueryAjax convencional
 *
 * @return void
 */
dhtmlXGridObject.prototype.setCellMMS = function(rowId, colId, value) {
	if ( U.isEmpty(rowId) || U.isEmpty(colId) || U.isEmpty(value) ) {
	  console.error('As var�aveis (rowId, colId, value) da fun��o setCelMMS n�o podem ser vazias');
	  return false;
	}
	
	var colIndex = this.getColIndexById(colId);
	
	this.cells(rowId, colIndex).setValue(value);
}

/*
 * Obt�m o valor da celula baseado nos ids da linha e coluna do grid
 * 
 * @autor Charlan Santos
 * @package dhtmlxGrid
 * @param string rowId - id da linha
 * @param string colId - id da coluna
 * @param string value - Um objeto com as mesmas opcoes de um JqueryAjax convencional
 *
 * @return void
 */
dhtmlXGridObject.prototype.getCellMMS = function(rowId, colId) {
	if ( U.isEmpty(rowId) || U.isEmpty(colId) ) {
	  console.error('As var�aves (rowId, colId, value) da fun��o setCelMMS n�o podem ser vazias');
	  return false;
	}
	
	var colIndex = this.getColIndexById(colId);
	
	return this.cells(rowId, colIndex).getValue();	
}

/*
 * Cria uma nova linha do grid baseado nos ids das colunas e n�o nos indices como � na func�o original
 * 
 * @autor Charlan Santos
 * @package dhtmlxGrid
 * @param string newId - id da linha
 * @param object data - data ex: {ID_COLUNA: 'DADO1', ID_COLUNA2:'DADO2'}
 * @param int position - posi��o (opcional)
 *
 * @return void
 */
dhtmlXGridObject.prototype.addRowMMS = function(newId, data, position) {

	position = (position || null);
	a = [];
	totalCols = this.getColumnsNum();
	
	for (var i = 0; i < totalCols; i++) {

		colId = this.getColumnId(i);

		if (typeof data[colId] == "undefined") {
		    a.push('');
		} else {
		    a.push(data[colId]);
		}
	}
    	
	(position == null) ? this.addRow(newId, a) : this.addRow(newId, a, position);
}
	
dhtmlXGridObject.prototype.getTotalCheckedRowsMMS = function(indexCheckboxColumn) {
	var count = 0,
		index = (indexCheckboxColumn || 0);

	SYSTEM.Grid.forEachRow(function(id){
		count += parseInt(SYSTEM.Grid.cells(id, index).getValue());
	});

	return count;
}

dhtmlXGridObject.prototype.getValueCheckedRowsMMS = function(indexColData, indexColCheckbox) {
    	indexColCheckbox = (indexColCheckbox || 0);
    	data = [];

    	if (typeof indexColData != "number") {
		indexColData = this.getColIndexById(indexColData);
	}

    	this.forEachRow(function(id){
		if(this.cells(id, indexColCheckbox).getValue() == '1'){
	       		data.push(this.cells(id, indexColData).getValue());
	         }
	});

	return data.join(',');
}

dhtmlXGridObject.prototype.loadXMLMMS = function(url, callback) {
	that = this;
        callback = (callback || function(){});
	$.ajax({
	   type: 'post',
	   url:  url,
	   success: function(retorno){
		   try {
                            callback();
			   xmlDoc = $.parseXML(retorno);
			   that.parse(retorno);
		   } catch (erro) {
			   var json = JSON.parse(retorno);
			   if (typeof json.msgPermissaoAction != "undefined") {
					dhtmlx.alert({
						title:'Erro',
						type:'alert-error',
						text: json.msgPermissaoAction
					});
			   }
		   }
		}
	})
}
dhtmlXGridObject.prototype.serializeToJsonMMS = function(a) {

		if(!this.getRowsNum()) {
			return [];
		}

        // loop nas colunas da grid para pegar os IDs / return array
        arrayKey = [];
        var totalColumns = this.getColumnsNum(),
            indice ='';

        for (i = 0; i < totalColumns; i++) {
            indice = this.getColumnId(i) ? this.getColumnId(i) : i;
            arrayKey.push(indice);
        }

        // SerializeToCSV para pegar os dados da grid / return array
        this.setCSVDelimiter("||@#MMS;MMS#@||");
        dataGrid = this.serializeToCSV();
        arrayData = dhx.csvToArray(dataGrid,'||@#MMS;MMS#@||');

        for (i in arrayData) {
            // combinar: valor do array com os IDs (chave) com as linhas da grid serializado (value) / return json
            arrayData[i] = dhx.arrayCombineJson(arrayKey, arrayData[i]);
        }

        return arrayData;

}

dhtmlXGridObject.prototype.serializeToJsonStrMMS = function(a) {
	return JSON.stringify(this.serializeToJsonMMS(a));
}

/*
 * Limpa todos os filtros do cabe�alho do grid
 * 
 * @autor Mateus Dutra
 * @package dhtmlxGrid
 *
 * @return void
 */  
dhtmlXGridObject.prototype.clearFiltersMMS = function(){
	for (var i=0;i<this.filters.length;i++){
		switch(this.filters[i][0].tagName.toLowerCase()){
			case "input":this.filters[i][0].value="";break;
			case "select":this.filters[i][0].selectedIndex=0;break;
			case "div":this.filters[i][0].combo.setComboValue("");break
		}
	} this.filterByAll()
}
	

/*
 * Retorna um array com as colunas do grid
 * 
 * @autor Eduardo M. Pereira
 * @package dhtmlxGrid
 * @return array 
 */
dhtmlXGridObject.prototype.getColumnsIdMMS = function(){
	var colNum = this.getColumnsNum();
	var a = [];
	for (var i=0;i<colNum;i++){
		a.push(this.getColumnId(i));
	}
	return a;
} 	

dhtmlXGridObject.prototype.getClassNameMMS = function(){return 'dhtmlXGridObject';}

/*
 * Copiar valor da grid (copyCellMMS +  copySelectedBlockMMS)
 * 
 * @autor Eduardo M. Pereira
 * @param bool CtrlC - habilita a copia pelo atalho Ctrl+C e selecionar a Grid
 * @param bool doubleClick - habilita a copia com dois clicks
 * @param string/bool msgCopy - mensagem que � exibida ao copiar o valor
 * @return mensagem / void 
 */
dhtmlXGridObject.prototype.enableCopyMMS = function (CtrlC, doubleClick, msgCopy) {
	
	// event keyPress - Ctrl+C (copy)
	if (typeof CtrlC == 'undefined' || CtrlC == true){
		this.attachEvent("onKeyPress",onKeyPressed);
		function onKeyPressed(code,ctrl,shift){
			if(code==67&&ctrl){this.copySelectedBlockMMS(msgCopy);}
			return true;
		}
		this.enableBlockSelection();
	}
	
	// event double click - copy cell
	if (typeof doubleClick == 'undefined' || doubleClick == true){
		this.attachEvent("onRowDblClicked", function(rId, cInd){
			this.copyCellMMS(rId, cInd, msgCopy);
		});
	}
}

/*
 * Copiar valor da celula - add valor na area de transferencia
 */
dhtmlXGridObject.prototype.copyCellMMS = function (rId, cInd, msgCopy) {
	U.copyStr(this.cells(rId, cInd).getValue(), msgCopy);
}

/*
 * copiar valor do bloco selecionado - add valor na area de transferencia
 */
dhtmlXGridObject.prototype.copySelectedBlockMMS = function (msgCopy) {
	var Selected = this.getSelectedBlock(), strCopy = '';
	if(Selected){
		for(var i = parseInt(Selected.LeftTopRow); i <=  parseInt(Selected.RightBottomRow); i++) {
			for(var j = Selected.LeftTopCol; j <=  Selected.RightBottomCol; j++) {
				
				if(typeof Selected.LeftTopRow ==  'string'){
					cell = this.cells(i, j);
				} else {
					cell = this.cells(this.getRowId(i), j);
				}
				
				strCopy = strCopy + cell.getValue() + '	';
				
			}
			strCopy = strCopy + "\n";
		}
		U.copyStr(strCopy, msgCopy);
	}
}


dhtmlXGridObject.prototype.getCellTextSelected = function(colId) {
	return this.cells(this.getSelectedRowId() , this.getColIndexById(colId)).getValue();
}

/************************************************ FIM DOS M�TODOS DHTMLX GRID ***************************************************************/



U.isEmpty = function(value) {
  return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
}

U.setContainerDataInsideFormData = function(containers, Form) {
	if ( Object.keys(containers).length > 0 ) {
			var objectName,
				dataComponent = {},
				dc;
			
			$.each(containers, function(modelName, component) {
				objectName = component.getClassNameMMS();
				
				if (typeof U.getData[objectName] == 'function') {
					dc = U.getData[objectName](component);
					dataComponent[modelName] = dc;
				}	
			});
			
		    if (typeof Form == 'function') {
		    	Form.setFormData(dataComponent);
		    } else if (typeof C7 == 'function'){
		    	C7.setFormData(dataComponent);
		    }
	}
}

U.getInputFilesData = function(inputFiles, ajaxParams) {	
	var formData = new FormData(),
		extraData = ajaxParams.data;
	
	// Seta parametros necess�rios para a request funcionar com input file
	ajaxParams.cache = false;
	ajaxParams.contentType = false;
	ajaxParams.processData = false;

	// TODO: Hoje a linha abaixo obt�m apenas o primeiro input file do document atual
	// Implementar um loop que percorre a var inputFiles construindo um array de objetos Files
	file = inputFiles[0].files[0];
	formData.append("filesMMS", file);
	
	// Inserindo o dados extras do form no obj formdata (requisito para funcionar com input file)
	if (typeof extraData != 'undefined' && Object.keys(extraData).length > 0) {
		$.each(extraData, function(k,v){
			formData.append(k, v);
		});
	}
	
	ajaxParams.data = formData;
	
	return ajaxParams;
}

U.getData = {
	dhtmlXForm: function(Form){
	   if (typeof Form == 'function') {
		  return Form.getFormData();
	    } else if (typeof C7 == 'function'){
	    	return Form.getFormData();
	    }
	},
	
	dhtmlXGridObject: function(Grid){
		return Grid.serializeToJsonMMS();
	}
}

U.sort = function(a, b){
	var c = b;
	return a.sort(function(a, b){return (c) ? b - a : a - b;})
}

/*
 * Recebe uma string e add a area de transferencia (Ctrl+C)
 *
 * @autor Eduardo M. Pereira
 * @param str string que sera adicionada na area de transferencia
 * @param msgCopy mensagem ("copiado...") que ser� exibida ao concluir / para nao exibir passar FALSE
 */
U.copyStr = function (str, msgCopy) {
	document.oncopy = function(event) {
		event.clipboardData.setData("Text", str);
		event.preventDefault();
	};
	document.execCommand("Copy");
	document.oncopy = undefined;
	if(msgCopy !== false){
		msgCopy = (typeof msgCopy == "string") ? msgCopy : "copiado...";
		dhtmlx.message({text: msgCopy, expire:1500, type:"classCSS"});
	}
}


U.downloadURI = function (uri, fileName) {
	var link = document.createElement('a');
	link.download = fileName;
	link.href = uri;
	link.click();
}

/*
 * Atribui para o Obj FormData Nativo os valores checados de checkboxes  
 * e ja os prepara para serem utilizados com o método GlobalModel\SaveMultiple
 * 
 * @autor Charlan Santos
 * @param config objeto com seguinte padrão:
 * {modelName: nameCheckbox, modelName2: nameCheckbox2}
 * 
 * Nota: O name do checkbox deverá ser igual ao nome do campo da tabela
 * em que os valores serão salvos
 * 
 */
dhtmlXForm.prototype.setFormDataComboCheckbox = function( config, setForm, col ) {
	marr = [];
	for(var k in config) {
		
		fieldName = config[k];
		modelName = k;		
		
			
		if (typeof setForm == 'undefined') {
			setForm = this;
		}
		
		checkedValues = this.getCombo(fieldName).getChecked();
		
		if (typeof setForm.getFormData()['MULTIPLE'] == 'undefined') {			
			setForm.setFormData({
				"MULTIPLE" : []
			});
		}
		
		for(i2 in checkedValues) {
			marr.push({[col] : checkedValues[ i2 ]});
		}
	}
	
	setForm.getFormData()['MULTIPLE'].push(marr);
	
}

U.btnTopCell = function (onclick, title, icon, id, inactive) {
	onclick = (typeof onclick != "string") ? "" : " onclick='" + onclick + "'";
	title = (typeof title != "string") ? "" : " title='" + title + "'";
	icon = (typeof icon != "string") ? "" : "icon-" + icon;
	id = (typeof id != "string") ? "" : " id='" + id + "'";
	inactive = (inactive === true) ? " disabled " : "";
	return "<button " + id + onclick + title + inactive + " class='button-flat button-cell-icon " + icon + "'></button>";
}


/*
 * Prepara os dados afim de serem utilizados pelo método GlobalBaseController/globalSaveRelated
 *
 * @autor Charlan Santos
 * @param config
 * 
 */
U.prepareSaveRelated = function (config) {

	temp = U.getParentChildObj(config);

	// dados do parentObj
	poData = temp[0];

	// dados do childObj
	coData = temp[1];
	
	arrSR = {};
	arrSR['PAIS_FILHOS'] = {};

	if ( typeof coData[0][0] != "undefined" ) {		
		coData = coData[0];
	}
	
	if ( typeof poData[0][0] != "undefined" ) {
		poData = poData[0];
	}
	
	for (i in poData) {
		arrSR['PAIS_FILHOS'][i] = {};
		arrSR['PAIS_FILHOS'][i][0] = poData[i];
		arrSR['PAIS_FILHOS'][i][1] = [];
		arrSR['PAIS_FILHOS'][i][1].push(coData[i]);
	}
	
	arrSR['PARAMS'] = {
			'relacao': config.relacao, 
			'modelPai': config.modelPai,
			'flgAtivo': config.flgAtivo,
			'transacao': config.transacao,
			'scenarioModelFilho': config.scenarioModelFilho,
	}

	r = {};
	r['SR_' + config.modelPai] = arrSR;
	
	return r;
}


U.getParentChildObj = function(config){
	
	if (typeof config.objPai.getClassNameMMS != 'undefined') {
		var po = config.objPai.getClassNameMMS();			
	} else {
		var po = 'array';
	}

	if (typeof config.objFilho.getClassNameMMS != 'undefined') {
		var co = config.objFilho.getClassNameMMS();			
	} else {
		var co = 'array';
	}	
	
	pData = [];
	if (po == 'dhtmlXForm') {
		pData[0] = [];
		pData[0].push(config.objPai.getFormData());
		
		if (co == 'dhtmlXForm') {
			cData = config.objFilho.getFormData();
		} else if (co == 'dhtmlXGridObject') {
			cData = config.objFilho.serializeToJsonMMS();
		} else if(co == 'array') {
			cData = config.objFilho;
		}
		
	} else if (po == 'dhtmlXGridObject') {
		pData.push(config.objPai.serializeToJsonMMS());	
		
		if (co == 'dhtmlXForm') {
			cData = config.objFilho.getFormData();
		} else if (co == 'dhtmlXGridObject') {
			cData = config.objFilho.serializeToJsonMMS();
		} else if(co == 'array') {
			cData = config.objFilho;
		}
	} else if (po == 'array') {
		pData[0] = [];
		pData[0].push(config.objPai);
		
		if (co == 'dhtmlXForm') {
			cData = config.objFilho.getFormData();
		} else if (co == 'dhtmlXGridObject') {
			cData = config.objFilho.serializeToJsonMMS();
		} else if(co == 'array') {
			cData = config.objFilho;
		}
	}
	
	return [pData, [cData]];
}



/************************************************    FIM DOS MÉTODOS DHTMLX GRID   *****************************************************************/

/************************************************    MÉTODOS DHTMLX LAYOUT   *****************************************************************/

	/*
	 * Add botao no titulo da cell do layout
	 */
	dhtmlXLayoutObject.prototype.addBtnTitleMMS = function (btns, cell, collapse) {
		var cell = (cell || 'a'),
			 collapse = (collapse || false),
			 strBtns = '';
		for ( var i in btns ) { strBtns = btns[i] + strBtns; }
		this.setText( cell, this.getText(cell) + strBtns );
		if (!collapse) {
			this.cells(cell).hideArrow();
			align_button_cell();
		}
	}

/************************************************   FIM DOS MÉTODOS DHTMLX LAYOUT   *****************************************************************/ 

