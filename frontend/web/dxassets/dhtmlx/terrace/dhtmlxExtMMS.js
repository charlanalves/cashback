U = {};
// TODO: Alterar esse arquivo de .js para .php e carrega-lo num evento global do Yii 2


/************************************************ MÉTODOS DHTMLX FORM ***********************************************************************/
/*
 * Realiza a requisição ajax com jquery enviando os dados do form(getFormData)
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

	if (typeof Form == 'function' && typeof Form.beforeSendMMS == 'function') {
		Form.beforeSendMMS();
    } else if (typeof C7 == 'function' && typeof C7.beforeSendMMS == 'function'){
	    C7.beforeSendMMS();
    } else if (typeof M7 == 'function' && typeof M7.beforeSendMMS == 'function'){
        M7.beforeSendMMS();
    }

	ajaxParams.type = (ajaxParams.type || 'POST');
	ajaxParams.data = (ajaxParams.data || {});


	// Se tiver container com grid no form será automaticamente serializado
	// e seus os dados e setados no objeto formData para ser enviado na requisição
	U.setContainerDataInsideFormData(containers, this);

	var data = this.getFormData();
	$.extend(ajaxParams.data, data);


	if ( inputFiles.length > 0 ) {
		// Se tiver input file no form o mesmo será preparado para ser enviado na requisição automaticamente
		var newAjaxParams = U.getInputFilesData(inputFiles, ajaxParams);

		$.extend(ajaxParams, newAjaxParams);
	}

	$.ajax(ajaxParams);
}
/*
 * Realiza a requisição ajax com jquery enviando os dados do form(getFormData)
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
				// Para resolver o problema da tradução das mensagens como abaixo por exemplo
				dhtmlx.alert({
					title: "Atenção!",
					type: "alert-error errorCustom",
					text: 'Erro ao carregar o form.',
					width: "100%",
				});
			}
	}

	this.sendMMS(ajaxParams);
}

/*
 * Obtém os objetos dos components de todos containers do form
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
			}else if(typeof M7.grid[name] != 'undefined'){
				components[name] = M7.grid[name];
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
		var width = document.querySelectorAll('.dhtmlx_window_active .dhxform_base')[0].clientWidth + 30;
		var heights = [];
		$(".dhtmlx_window_active .dhxform_base").each(function(k,v){ heights.push(this.clientHeight);})
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

dhtmlXCombo.prototype.setComboValueMMS = function(a) {    
    for (var b = 0; b < this.optionsArr.length; b++)
        if (this.optionsArr[b].data()[0] == a) return this._skipFocus = !0, this.selectOption(b, null, !0);
    this.DOMelem_hidden_input.value = a
}



/************************************************ FIM DOS MÉTODOS DHTMLX FORM ***************************************************************/


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


dhtmlXCombo.prototype.attachChildComboMMS = function(a, b, form, itemName, params, hide = true, onFirstOpt = false, callback = function(){}) {

        this._child_combos || (this._child_combos = []), this._has_childen = 1, this._child_combos[this._child_combos.length] = a;
      
	        if (hide) {
	        	a.show(0);
	        	var currentValue = this.getActualValue();
	        	if (currentValue != ''){
					form.showItem(itemName);
				} else {
					form.hideItem(itemName);
				}
	        }
	     		
	        var c = this,
	            d = arguments.length;
	       
			
	        this.attachEvent("onChange", function() {
			var currentValue = this.getActualValue();
			cc = form.getCombo(itemName);
			
		
			
			if (hide) {
				if (currentValue != ''){
					form.showItem(itemName);
				} else {
					form.hideItem(itemName);
				}
			}
			
			oldb = b;
			if (typeof params != 'undefined') {
				oldParams = params
				params.whereParams = {};
				if (typeof params.where != 'undefined') {
					for (i in params.where){
						try{
							params.whereParams[i] = eval('M7.' + params.where[i]);
						}catch(e){}
					}
				}
			}
			
			if (typeof eval(b) == 'function' && b) {
            	var url = eval(b)();
            	if (!url) {
            		
            		return false;
            		}
            }else{
            	params = (typeof params == "object" ? JSON.stringify(params) : params);			
				b += '&currentValue='+ currentValue +'&params='+ params;
				var url = b;
	        }
			
            for (var e = 0; e < c._child_combos.length; e++) {
            	if (hide){
            		a.show(1)
            	}
            	c._child_combos[e] == a && (a.callEvent("onMasterChange", [c.getActualValue(), c]));
            }
            
            
          
            if (currentValue != '') {
            	
            	cc.ev_onChange.removeTempEventMMS(cc);
	            if(hide){
	            	
		            "" == c.getActualValue() ? c.showSubCombo(c, 0) : a._xml ? (1 == d && (b = a._xml), a._xml = c.deleteParentVariable(b), a._xml += (a._xml.indexOf("?") != -1 ? "&" : "?") + "parent=" + encodeURIComponent(c.getActualValue())) : b && (a.clearAll(!0), 
		            		
		            		a.loadXML(url, function(){
		            				 callback();
			           				 cc.ev_onChange.addTempEventMMS(cc);
			           				return;
			           				 
		            			})
		            )
		            }else {
		            	
		            	"" == c.getActualValue() ? a.show(1): a._xml ? (1 == d && (b = a._xml), a._xml = c.deleteParentVariable(b), a._xml += (a._xml.indexOf("?") != -1 ? "&" : "?") + "parent=" + encodeURIComponent(c.getActualValue())) : b && (a.clearAll(!0), 
		            			
		            			a.loadXML(url, function(param){		            				
		            				callback();
		            				param.mainObject.ev_onChange.addTempEventMMS(param.mainObject);	
		            				return;
		            				
		            			})
		            			
	        			)
		            }
            
            } else {
            
            	cc.ev_onChange.removeTempEventMMS(cc);
            	a.loadXML(url, function(param){            	
            		callback();
    				param.mainObject.ev_onChange.addTempEventMMS(param.mainObject);
    			})
            }
            
            b = oldb;
            if (typeof oldParams != "undefined"){
            	params = oldParams;
	        }
            })
}
/************************************************    FIM DOS MÉTODOS DHTMLX COMBO   *****************************************************************/

/************************************************    MÉTODOS DHTMLX GRID   *****************************************************************/






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
	if ( U.isEmpty(rowId) || U.isEmpty(colId) ) {
	  console.error('As variáveis (rowId, colId) da função setCelMMS não podem ser vazias');
	  return false;
	}

	var colIndex = this.getColIndexById(colId);

	this.cells(rowId, colIndex).setValue(value);
}

/*
 * Obtém o valor da celula baseado nos ids da linha e coluna do grid
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
	  console.error('As variáveis (rowId, colId, value) da função setCelMMS não podem ser vazias');
	  return false;
	}

	var colIndex = this.getColIndexById(colId);

	return this.cells(rowId, colIndex).getValue();
}

/*
 * Cria uma nova linha do grid baseado nos ids das colunas e não nos indices como é na funcão original
 *
 * @autor Charlan Santos
 * @package dhtmlxGrid
 * @param string newId - id da linha
 * @param object data - data ex: {ID_COLUNA: 'DADO1', ID_COLUNA2:'DADO2'}
 * @param int position - posição (opcional)
 *
 * @return void
 */
dhtmlXGridObject.prototype.addRowMMS = function(newId, data, position) {

	position = (position || null);
	a = [];
	totalCols = this.getColumnsNum();

	for (var i = 0; i < totalCols; i++) {

		colId = this.getColumnId(i);

		if (typeof data[colId] == "undefined" || data[colId] == null) {
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

	this.forEachRow(function(id){
		count += parseInt(this.cells(id, index).getValue());
	});

	return count;
}

dhtmlXGridObject.prototype.getValueCheckedRowsMMS = function(indexColData, indexColCheckbox, outputObject = false) {
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
        
        if (!outputObject) {
            return data.join(',');
        }
        
        return data;
}

dhtmlXGridObject.prototype.loadXMLMMS = function(url) {
	that = this;
	$.ajax({
	   type: 'post',
	   url:  url,
	   success: function(retorno){
		   try {
			   xmlDoc = $.parseXML(retorno);
			   that.parse(retorno);
                           $.unblockUI()
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
 * Limpa todos os filtros do cabeçalho do grid
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
 * @param string/bool msgCopy - mensagem que é exibida ao copiar o valor
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


/*
 * 
 *
 * @autor 
 * @param int colInd - habilita a copia pelo atalho Ctrl+C e selecionar a Grid
 * @param string text - habilita a copia com dois clicks * 
 * @return
 */
dhtmlXGridObject.prototype.addMultiFilter = function (colInd, text) {	
	addMultiFilter(this, colInd, text);
}

    dhtmlXGridObject.prototype.getChangedRowsMMS = function(col) {
            var b = {checked:[],unchecked:[]};

             this.forEachRow(function(c) {
                    var d = this.rowsAr[c];
                    if ("TR" == d.tagName) {
                        var e = d.childNodes.length;
                        if (d.childNodes[col].wasChanged) {
                            if (this.cells(c,col).isChecked()){
                                b.checked.push(d.idd);
                            }else {
                                b.unchecked.push(d.idd);
                            }
                        }
                    }
            });

            return b;
    }

/************************************************ FIM DOS MÉTODOS DHTMLX GRID ***************************************************************/



/************************************************    METODOS DHTMLX LAYOUT   *****************************************************************/

/*
 * Add botao no titulo da cell do layout
 */
dhtmlXLayoutObject.prototype.addBtnTitle = function (btns, cell, collapse) {
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

/************************************************    METODOS DHTMLX LAYOUT   *****************************************************************/


/************************************************    METODOS DHTMLX WINDOW  *****************************************************************/

/************************************************    METODOS DHTMLX WINDOW   *****************************************************************/




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
					
					if (typeof M7.gParams.saveRelated == 'object'){
				
						var datap = U.prepareSaveRelated({
							'objPai':Form,
							'objFilho':M7.grid[modelName],
							'relacao': M7.gParams.saveRelated.relacao,
							'modelPai':M7.gParams.saveRelated.modelPai,
							'flgAtivo':M7.gParams.saveRelated.flgAtivo,
							'transacao':M7.gParams.saveRelated.transacao,
							'scenarioModelFilho':M7.gParams.saveRelated.scenarioModelFilho,
						});
						dataComponent = datap['SR_'+M7.gParams.saveRelated.modelPai];	
					}else {
						dataComponent[modelName] = dc;
						
					}
				}
			});

			
			M7.gParams.saveRelated = false;
	    	Form.setFormData(dataComponent);

	}
}

U.getInputFilesData = function(inputFiles, ajaxParams) {
	var formData = new FormData(),
		extraData = ajaxParams.data;

	// Seta parametros necessários para a request funcionar com input file
	ajaxParams.cache = false;
	ajaxParams.contentType = false;
	ajaxParams.processData = false;

	// Percorre a var inputFiles construindo um array de objetos Files
	$.each(inputFiles, function(index, value) {
		file = inputFiles[index].files[0];
		name = "filesMMS" + ((index > 0) ? index : '');
		formData.append(name, file);
	});

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
		return Form.getFormData();
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
 * @param msgCopy mensagem ("copiado...") que será exibida ao concluir / para nao exibir passar FALSE
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

/*
 * Atribui para o Obj FormData Nativo os valores checados de checkboxes
 * e ja os prepara para serem utilizados com o mÃ©todo GlobalModel\SaveMultiple
 *
 * @autor Charlan Santos
 * @param config objeto com seguinte padrÃ£o:
 * {modelName: nameCheckbox, modelName2: nameCheckbox2}
 *
 * @param gridObj (opcional) objeto Grid - Enviar o Grid quando desejar
 * incluir os dados do checkbox no userData do mesmo. Isso possiblita salvar o
 * Grid enviando tambÃ©m os dados do form caso esteja usando o mÃ©todo bind ou
 * lÃ³gica parecida
 *
 * Nota: O name do checkbox deverÃ¡ ser igual ao nome do campo da tabela
 * em que os valores serÃ£o salvos
 *
 * renomeado com 2 na frente para diferenciar do metodo acima por não saber qual metodo é o certo.
 */
dhtmlXForm.prototype.setFormDataComboCheckbox2 = function( config , gridObj ) {

	for(var k in config) {

		fieldName = config[k];
		modelName = k;

		checkedValues = this.getCombo(fieldName).getChecked();

		if (typeof this.getFormData()['MULTIPLE'] == 'undefined') {
			this.setFormData({
				"MULTIPLE" : []
			});
		}

		if (typeof this.getFormData()['MULTIPLE'][modelName] == 'undefined') {
			this.getFormData()['MULTIPLE'][modelName] = [];
		}

		for(i2 in checkedValues) {
			this.getFormData()['MULTIPLE'][modelName].push({ [ fieldName ] : checkedValues[ i2 ] })
		}

		if (typeof gridObj  != 'undefined') {
			gridObj.setUserData("","MULTIPLE",Form.form['EmbalagemManMecan'].getFormData()['MULTIPLE']);
		}
	}
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
		if (poData.length == 1){
			arrSR['PAIS_FILHOS'][i][1] = coData;
		}else{		
			arrSR['PAIS_FILHOS'][i][1].push(coData[i]);
		}
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

/*
 * Desabilita / Habilita a celula da grid
 *
 * @autor Eduardo M. Pereira
 * @param string Id da linha
 * @param string/array Id da(s) coluna(s)
 * @param bool desabilita(padrao) ou habilita (false)
 * @return void
 */
 dhtmlXGridObject.prototype.disableCellMMS = function (rowId, colId, status) {
	var status = (typeof status == "undefined") ? true : status;
	if (typeof colId == "object") {
		for (var i in colId) {
			this.cells(rowId, this.getColIndexById(colId[i])).setDisabled(status);
		}
	} else {
		this.cells(rowId, this.getColIndexById(colId)).setDisabled(status);
	}
 }

 /*
 * Usado para ordenar colunas de data no dhtmlx
 *
 * @autor Vitor Hallais
 *
 * Exemplo:
 * ['sets' => ['title' => Yii::t("ECM",'DT. INICIAL'), 'width'=>'116', 'type'=>'ro', 'sort'=>'dateMMS', 'id'  => 'ECM13_DT_INICIAL_PREVISTA' ]];
 */
 function dateMMS(a,b,order){
 	a=a.split("/")
 	b=b.split("/")
 	if (a[2]==b[2]){
 		if (a[1]==b[1])
 			return (a[0]>b[0]?1:-1)*(order=="asc"?1:-1);
 		else
 			return (a[1]>b[1]?1:-1)*(order=="asc"?1:-1);
 	} else
 		return (a[2]>b[2]?1:-1)*(order=="asc"?1:-1);
 };


/*
 * Usado para ordenar colunas de data/hora no dhtmlx
 *
 * @autor Vitor Hallais
 *
 * Exemplo:
 * ['sets' => ['title' => Yii::t("ECM",'DT. INICIAL'), 'width'=>'116', 'type'=>'ro', 'sort'=>'dateTimeMMS', 'id'  => 'ECM13_DT_INICIAL_PREVISTA' ]];
 */
function dateTimeMMS(a, b, order) {
    a = a.split("/");
    b = b.split("/");

    var aOk = 0;
    var bOk = 0;

    if (typeof a[2] === 'string' || a[2] instanceof String) {
        // separa o ano da hora
        var timeA = a[2].split(" ");
        a[2] = timeA[0];
        timeA = timeA[1].split(":");
        aOk = 1;
    }

    if (typeof b[2] === 'string' || b[2] instanceof String) {
        var timeB = b[2].split(" ");
        b[2] = timeB[0];
        timeB = timeB[1].split(":");
        bOk = 1;
    }

    if (aOk != bOk || (aOk == bOk && aOk == 0))
        return (aOk > bOk ? 1 : -1) * (order == "asc" ? 1 : -1);

    if (a[2] == b[2]) {
        if (a[1] == b[1]) {
            if (a[0] == b[0]) {
                if (timeA[0] == timeB[0]) {
                    if (timeA[1] == timeB[1])
                        return (timeA[2] > timeB[2] ? 1 : -1) * (order == "asc" ? 1 : -1);
                    else
                        return (timeA[1] > timeB[1] ? 1 : -1) * (order == "asc" ? 1 : -1);
                } else
                    return (timeA[0] > timeB[0] ? 1 : -1) * (order == "asc" ? 1 : -1);
            } else
                return (a[0] > b[0] ? 1 : -1) * (order == "asc" ? 1 : -1);
        } else
            return (a[1] > b[1] ? 1 : -1) * (order == "asc" ? 1 : -1);
    } else
        return (a[2] > b[2] ? 1 : -1) * (order == "asc" ? 1 : -1);
}
;
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
