<script type="text/javascript" charset="utf-8">
var Validation = function() {	
	var errorMessage = '';
		
	this.setErrorMessage = function(value) {
		if (errorMessage == '' && typeof value != 'undefined') {
			errorMessage = value;
		}
	}

	this.getErrorMessage = function(value) {
		return errorMessage; 
	}

	this.validate = function(actionRules, validator) {

		var values = actionRules[0];
		
		// Verifica se a validação é do formato : mensagem de erro por elemento (Grid, variável etc)
		 if (typeof values[0]['value'] != "undefined") {	
			 
			for (var i in values) {
				var value = values[i]['value'],
					message = values[i]['message'];
				
				this.setErrorMessage(message);
				
				return this.runValidate(value, validator);
			}
		 } else {
			// Executa a validacao com o formato: mensagem de erro por action
			return this.runValidate(values, validator);
		 }		 
	}

	
	this.runValidate = function(value, validator) {
		var error = true;
		
		error = this.hasError(value, validator);
		
		if ( error ) {
			C7.FirstErrorValidate = {value: SYSTEM[value], error: this.errorMessage };
			return true;
		}
		
		return false;
	}

	this.hasError = function(value, validator) {
		
		if (typeof SYSTEM[value] != "undefined" ) {
			
    		error = this[validator](SYSTEM[value]);
    		
		} else if(typeof C7[value] != "undefined" ) {
			
			error = this[validator](C7[value]);
			
		} else {
			
			error = this[validator](value);
		}

		return error;
	}
	
	this.isEmptyGrid = function(grid) {	
		var msg = "<?= Yii::t("app", "O grid não pode ficar vazio.")?>";
		
		this.setErrorMessage(msg);

		if ( this['_gridIsEmpty'](grid) ) {
			return true;
		}
		
		return false;
	}

	
	this._gridIsEmpty = function(grid) {	
		return (C7.totalRowsVisibleGrid(grid)) ? false : true;
	}
	

	this.isAnyRowChecked = function(grid) {
		var msg = "<?= Yii::t("app", "Gentileza selecionar um registro no grid.") ?>";
		
		this.setErrorMessage(msg);

		if ( grid.getTotalCheckedRowsMMS() == 0 ) {			
			return true;
		}
		
		return false;
	}

	this.isAnyRowSelected = function(grid) {
		return (grid.getSelectedRowId() == null) ?  true : false; 
	}
	
};

var C7 = function() {};
SYSTEM = {};

C7.main = {};
C7.main.modal = {};
C7.FilterA = {};
C7.form = {};
C7.grid = {};
C7.layout = {};
C7.formOpen = true;
C7.sendForm = true;
C7.async = true;
C7.params = {};


C7.crud = {};
C7.crud.modal = {};
C7.FilterA = {};


C7._init = function(conf) {
	$.extend(this.settings, conf);
	
	C7.load('Toolbar', 'Main');	
	C7.load('Window', 'Main');	
}

C7.getToolbarMain = function() {
	SYSTEM.Toolbar.titulo(this.settings.toolbarTitle);
	SYSTEM.Toolbar.setIconesAcoes([this.settings.toolbarBtn]);
	SYSTEM.Toolbar.core.attachEvent("onClick", C7.Toolbar);
}	

C7.getWindowMain = function() {
	
	SYSTEM.Windows = new dhtmlXWindows();
	
    SYSTEM.Windows.createWindow("main", 0,0,1500, 1000);

    C7.windowMain = SYSTEM.Windows.window("main");
    
    C7.windowMain.button('minmax1').hide();
    C7.windowMain.button('park').hide();
    C7.windowMain.denyResize();
    C7.windowMain.center();    
    C7.windowMain.hide();
	
    C7.windowMain.attachEvent("onClose", function(win){
    	C7.close();
    });
}

C7.afterLoadFormCrud = function(formName) {

	var selectedRow = SYSTEM.Main.getRowIndex(SYSTEM.Main.getSelectedRowId());

	C7.main.bind(SYSTEM.Main);
	
	SYSTEM.Main.clearSelection();

	SYSTEM.Main.selectRow(selectedRow);
}

C7.load = function(component, nameItem, target, param, autoLoad, btns) {	
	var componentName = 'get' + component + nameItem,
		fnLoad = "load" + component,
		errorMessage = 'Erro: Você tentou chamar a função '+componentName+'() mas ela não existe.';
		
	if (typeof C7[fnLoad] === 'function' && !(component === 'Grid' && typeof C7[componentName] === 'function')) {
	
		var fnName = (nameItem || C7.currentFormName),
			beforeLoadFnName = 'beforeLoad'+ component + fnName,		
			afterLoadFnName = 'afterLoad' + component + fnName;
	
		// Executa o evento BeforeLoadForm
		C7.callFunctionDynamically(beforeLoadFnName, '');
		
		// load especifico do componente
		C7[fnLoad](target, fnName, param, autoLoad, btns);
		
		// Executa o evento AfterLoadForm
		C7.callFunctionDynamically(afterLoadFnName, '');
		
	} else{
    	C7.callFunctionDynamically(componentName, errorMessage);
	}
}

C7.loadForm = function(target, fnName, param) {
	formData = C7.getFormData(fnName);
	target = (target || C7.windowMain);
	if (typeof target == 'string') {
    	C7.main = new dhtmlXForm(target, formData);
	} else {
		C7.main = target.attachForm(formData);
		C7.main.resizeWindowMMS(target);	
	}
	C7.currentForm = C7.main;
	C7.form[fnName] = C7.main;
	C7.attachEventClick();
	C7.attachMask();
}

C7.loadGrid = function(target, fnName, param, autoLoad, btns) {
	
	autoLoad = (typeof autoLoad == "boolean" ? autoLoad : true);
	layoutGrid = new dhtmlXLayoutObject(target, "1C");
	layoutGrid_A = layoutGrid.cells("a");
	
	layoutGrid_A.setText(( this.settings["titleGrid" + fnName] || "<?= Yii::t('app','Listagem ')?>"));
	
	objGrid =  layoutGrid_A.attachGrid();
        objGrid.enableMultiline(true);
        objGrid.enableAutoWidth(true);
        objGrid.setImagesPath("./dxassets/dhtmlx/terrace/imgs/");
	objGrid.init();
	objGrid.enableRowsHover(true, 'hover');
	objGrid.layout = layoutGrid;
	objGrid.layoutCell = layoutGrid_A;
	
	SYSTEM[fnName] = objGrid;
	C7.grid[fnName] = objGrid;
	
	C7['actionReloadGrid' + fnName] = function (param) {C7.systemReloadGrid(fnName, param);};
	
	if (autoLoad === true) {
		C7['actionReloadGrid' + fnName](param);
	}

	if ( typeof btns != 'undefined' ) {
		C7.setGridBtns(objGrid, btns, layoutGrid);
	}
	
	if ( typeof C7[ 'setToolbarGrid' + fnName ] != 'undefined' ) {
		C7.setGridBtns(objGrid, C7[ 'setToolbarGrid' + fnName ](), layoutGrid, fnName);
	}
	

}

C7.setGridBtns = function(grid, btns, layoutGrid, param = '') {
	for(i in btns){
		var action = btns[i]['action'],
			title =  btns[i]['title'],
			icon =  btns[i]['icon'],
			fnName = 'C7.action' + action + '("' + param + '")';
		
    	btn = U.btnTopCell(fnName, title, icon);
    	
    	layoutGrid.addBtnTitleMMS([btn]); 
	}
	
}

C7.systemReloadGrid = function(fnName, param) {
    
	param = (typeof param == "object" ? JSON.stringify(param) : param);
	var callbackLoadGrid = (typeof C7['callbackLoadGrid' + fnName] == "function" ? C7['callbackLoadGrid' + fnName] : function(){});
	SYSTEM[fnName].clearAll();
	urlLoad = this.settings.urlLoadGridPrefix + fnName + '&json=true&param=' + param;
	$.blockUI();
	SYSTEM[fnName].loadXMLMMS(urlLoad, function () {
            
            $.unblockUI();
		callbackLoadGrid();
		
	});
}


C7.FilterA.init = function() {
	
	var formData = C7.getFormData('FilterA');
	 
	C7.FilterA = SYSTEM.Layout.innerLayout.cells("a").attachForm();
	C7.FilterA.loadStruct(formData, 'json');
	C7.FilterA.setFocusOnFirstActive();

	C7.currentForm = C7.FilterA;
	
	C7.attachEventClick();
}  

C7.runActionClient = function(action, params, checkPermissions, validate) {	
	C7.runAction(action, checkPermissions, validate, params, false, 'client');
}

C7.runActionBackend = function(action, params,  checkPermissions, validate, sendForm) {
	C7.runAction(action, checkPermissions, validate, params, sendForm, 'backend');
}

C7.runActionC = function(action, params, callback, checkPermissions, validate) {	
	C7.runAction(action, checkPermissions, validate, params, false, 'client');
}

C7.runActionB = function(action, params, callback, sendForm, checkPermissions, validate) {
	C7.runAction(action, checkPermissions, validate, params, sendForm, 'backend', callback);
}


C7.Toolbar = function(itemId) {
 
	/* 
	* Seta a action atual com a action definida ao chamar a função 
	* SYSTEM.Toolbar.setIconesAndActions([{"adicionar": "create"},{ "atualizar": "update"}]); 
	*
	* Ou seja se o usuário clicar no botão 'adicionar' na toolbar será chamado a função create
	*
	* A action atual é usada para fazer um ajax para o backend. Ex:
	*
	* Se o C7.action == 'create', ao executar a função C7.executeAction será disparado uma 
	* requisição para o método create do controller atual.
	*/	
	var action = SYSTEM.Layout.icons[0][itemId];
	
	
	C7.runAction(action, true);
}


C7.runAction = function(action, checkPermissions, validate, params, sendForm, actionType, callback) {
 $.blockUI();
	var action = C7.capitalise(action),
    	checkPermissions = (checkPermissions || this.settings.checkPermissions),
    	validate = (validate || this.settings.validate),
    	actionType = (actionType || C7.actionType || this.settings.actionType),	
    	callback = (callback || C7.sendDatacustomCallback || this.settings.sendDatacustomCallback),
    	centerRequest = (C7.centerRequest || this.settings.centerRequest),		
    	methodPrefix = 'action',
	 	actionFnName =  methodPrefix + action,
	 	beforeActionFnName = 'beforeAction' + action,
		afterActionFnName = 'afterAction' + action,
		errorMessage = 'Você precisa criar uma função nesse padrão action'+action+'() para a ação '+ action + ' funcionar.';
		params = (params || C7.params || this.settings.params);
		C7.action = action;	
		// C7.sendForm = (sendForm === false)  ? false : (C7.sendForm === false)  ?  false : this.settings.sendForm;
		C7.sendForm = (typeof sendForm == "boolean")  ? sendForm : (typeof C7.sendForm == "boolean")  ? C7.sendForm : this.settings.sendForm;
		
	// Checa se o usuário logado tem permissão para executar a ação 
	hasPermission = (checkPermissions) ? C7.checkPermissions(action) : true;

	if (hasPermission) {

		if (validate) { 
			if (!C7.validate()) {
				return;
			}
		}
		 
    	// Executa o evento BeforeAction
    	C7.callFunctionDynamically(beforeActionFnName, '');
    	
    	if (actionType === 'client') {
    		// Executa a Action no cliente
        	C7.callFunctionDynamically(actionFnName, errorMessage, params);
		} else {
			// executa a Action no backend - obviamente deverá existir 
			// uma action com o mesmo nome no controller em questão
			C7.executeAction(centerRequest, callback, params);
		}		
    		
    	// Executa o evento AfterAction
    	C7.callFunctionDynamically(afterActionFnName, '');
    	
		C7.setDefaultValuesFields();	
	}
}

C7.validate = function() {
	var rules = C7.rules(),
		error = true,
		validator = '',
		message = '',
		actionRules ='',
		values = [];

		for ( i in rules ) {
			actionRules = rules[i][C7.action];
			for (k in actionRules) {	
								
				 values = actionRules[0];
				 validator = actionRules[1];
				 
				var validation = new Validation();
				 
				 if (typeof actionRules[2] != "undefined" && typeof actionRules[2]['message'] != "undefined") {
					 validation.setErrorMessage(actionRules[2]['message']);
 				 }			

				 if (typeof validation[validator] != "undefined") {
					 error = validation.validate(actionRules, validator);
					 
 				 } else {
    					if (typeof eval(validator) == 'function') {
    						error = eval(validator +'('+ values +')');
    					}
 				 }

				 
				 if (error) {
						dhtmlx.alert({
				    		title:"Atenção!", 
				    		type:"alert-error errorCustom", 
				    		text: validation.getErrorMessage(),     		
						});
					return false;
				}
									 
			}
			 
		}
		
		return true;
}



C7.rules = function() {
 	return [];	
}

C7.checkPermissions = function(action) {	
	ret = false;
	var fs = C7.settings,
		action = (action || C7.action),
		route = fs.currentModule + '/' + fs.currentController.toLowerCase() + '/' + 'valida-permissao-acao',
		param = '&rotaController=' + route + action,
		url = './index.php?r=' + route + param;
	 
	$.ajax({
	   type: 'get',
	   url:  url,
	   async: false,
	   success: function(response) {		   		   
		   if (typeof response.status == "undefined" || typeof response.status == true) {
            	ret = true;
		   } 
	   }
	});

	return ret;
}

C7.close = function() {
    if(typeof C7.windowMain !== 'undefined') {
	C7.windowMain.hide();
	C7.windowMain.setModal(false);
    }
}

C7.show = function() {
	C7.windowMain.show();
    C7.windowMain.setModal(true);

	fnNameAction = this.settings['titleWindow' + C7.action];
	if(fnNameAction !== 'undefined'){
		this.windowMain.setText(fnNameAction);
	}
	
}

C7.getGlobalCreateAndUpdateSets = function() {
	C7.currentForm = C7.main;
	C7.sendDatacustomCallback = '';	
	
	C7.show();
	
	C7.load('WindowSets', 'GlobalCreate');
	C7.load('Form', 'Crud', C7.windowMain);
		
	C7.setDefaultValuesFields(); 	

}
C7.getWindowSetsGlobalCreate = function() {
	
}

C7.actionGlobalCreate = function() {
	C7.grid.Main.clearSelection()
	C7.getGlobalCreateAndUpdateSets();
	C7.form.Crud.clear();
	C7.windowMain.setText(this.settings.titleWindowCreate);	
}

C7.actionGlobalUpdate = function() {
	C7.getGlobalCreateAndUpdateSets();	
	C7.windowMain.setText(this.settings.titleWindowUpdate);	
}

C7.actionRefresh = function() {
	C7.reloadGrid();
}

C7.actionGlobalInactivate = function() {	
	C7.actionGlobalDelete();
}

C7.actionGlobalDelete = function(grid) {
	C7.setDefaultValuesFields();

	 if (typeof grid == 'object') {
    	grid = (Object.keys(grid).length === 0 ) ? "Main": grid;
	} else {
		grid = (grid || "Main");
	}

	var rowId = SYSTEM[grid].getSelectedId(),
		params = {id: rowId},
		callback = 'reloadGrid',
		sendForm = false;
	
	dhtmlx.confirm({
		title: this.settings.titleWindowDelete,
		ok:'<?= Yii::t('app','Não ')?>',
		cancel:'<?= Yii::t('app','Sim ')?>',
		text: '<?= Yii::t('app','Excluindo Registro ')?>',
		callback:function(excluir){
			if (!excluir) {	
				C7.runActionB(C7.action, params, callback, sendForm);
			}
		}
	});
}

C7.actionReloadGrid = function(params) {
	C7.reloadGrid(params);
}

C7.reloadGrid = function(params) {
	if (typeof C7.settings.gridReload != 'undefined') {
    	if (typeof C7.settings.callbackReloadGrid == 'function') {
    		C7.settings.gridReload.load(C7.settings.urlReloadGrid, C7.settings.callbackReloadGrid);
    	} else {
    		if ( typeof SYSTEM[C7.settings.gridReload] != 'undefined') {
				SYSTEM[C7.settings.gridReload].loadXMLMMS(C7.settings.urlReloadGrid);
    		} else if ( typeof C7.settings.gridReload == 'function') {
    			C7.settings.gridReload.load(C7.settings.urlReloadGrid);
    		}
    	}
	}
}

C7.actionExportExcel = function() {
	var urlSend = '../libs/dhtmlx/excel/generate.php';
	SYSTEM.Grid.toExcel(urlSend, 'full_color');
}

C7.globalExportExcel = function(gridName, param) {
	C7.sendDatacustomCallback = function(a){
		if(typeof a.excel !== "undefined") {
			uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(a.excel);
			fileName = a.fileName;
			U.downloadURI(uri, fileName);
		} else {
			C7.alertAtencao((typeof a.message !== "undefined" ? a.message : "<?= Yii::t("app", "Não foi possivel gerar o excel")?>"));
		}
	}
	
	if(typeof param == "object") {
		param = JSON.stringify(param);
	}
	
	C7.runActionBackend("globalExportExcel", {grid: gridName, param: param}, false, false, false); 
}

C7.executeAction = function (centerRequest, callback, params) {	

	var url = C7.getUrlCurrentAction(centerRequest);
	params = (params || C7.params);
	
	if (C7.sendForm) {
    	C7.sendFormData(url, callback);	
	} else {
		C7.ajax(params, callback, C7.async, centerRequest);
	}

	C7.sendForm = C7.settings.sendForm;
	C7.async = C7.settings.async;
	C7.params = C7.settings.params;
	C7.centerRequest = C7.settings.centerRequest;
	C7.actionType = C7.settings.actionType;
	C7.sendDatacustomCallback = C7.settings.sendDatacustomCallback;	
}

C7.ajax = function (params, callback, async, centerRequest, url, type) {
	params = (params || '');
	async = async === false ? false : true;
	callback =  (callback || 'sendDataCallbackDefault');
	centerRequest = centerRequest === false ? false : true;
	url = (url ||C7.getUrlCurrentAction(centerRequest));

	
	if (async) {		
		if (typeof callback == 'function') {			
			$.post(url, params, callback)
		} else {
			$.post(url, params, C7[callback])
		}
	} else {
		return dhtmlxAjax.postSync(url, params);
	}

}

C7.ajaxJquery = function (ajaxParams, centerRequest) {

	ajaxParams.url = (ajaxParams.url ||C7.getUrlCurrentAction(centerRequest));
	
	ajaxParams.success = (ajaxParams.success || C7['sendDataCallbackDefault']);
	
	ajaxParams.dataType = (ajaxParams.dataType || 'xml');
	
	$.ajax(ajaxParams);
}


C7.getFormData = function(typeForm) {
	methodPrefix = 'getFormData';
	functionName = methodPrefix + typeForm;
	
	if (typeof C7[functionName] !== 'function') {
		console.warn('Erro: Você deve criar uma função nesse formato '+functionName+'() retornando o json do formulário.')
		return false;
	}   	

	return C7[functionName]();
}

C7.callFunctionDynamically = function(functionName, errorMessage, params) {
	params = (params || null);

	if (typeof C7[functionName] !== 'function') {	
		if (typeof errorMessage != 'undefined' && errorMessage != '') {			
			console.warn(errorMessage)
		}
		return false;
	}   	

	return (params === null) ? C7[functionName]() : C7[functionName](params);
}

C7.sendFormData = function(urlSend, callback) {
	 if ((callback == '' || typeof callback == 'undefined')) {
		callback = 'sendDataCallbackDefault';
	 }
	 
	var conf = {
			sendDataCustomCallback: 'sendDataCallbackDefault'
	 };
	 
	$.extend(conf, {sendDataCustomCallback: callback});

	var ajaxOptionsDefault = {
			url: urlSend,
			success: C7[conf.sendDataCustomCallback],
			data: C7.params
	}
		
	
	if (typeof C7.ajaxOptions != 'undefined' && typeof C7.ajaxOptions != 'undefined') {
		$.extend(C7.ajaxOptions.data, ajaxOptionsDefault.data);
	}
	
	$.extend(ajaxOptionsDefault, C7.ajaxOptions);
	
	C7.currentForm.sendMMS(ajaxOptionsDefault);
}

C7.callbackReload = function (loader, response) {	
	
	SYSTEM.Grid.clearAll(true);
	SYSTEM.Grid.loadXMLString(response);
}


C7.sendDataCallbackDefault = function(response) {
	$.unblockUI();
	if (response.status) {	
		 C7.reloadGrid();
		
		 if (response.message === 'undefined' || response.message == '') {
			response.message = "Operação realizada com sucesso!";
		 }

                dhtmlx.alert({
                   text: response.message,
                   ok: "ok"
                });

		 if (C7.formOpen) {
		 	C7.close();
		 }
	} else {
            
		if (response.message === 'undefined' || response.message == '') {
			response.message = "Erro ao realizar a operação.";
		}
		
		dhtmlx.alert({
                    title:"Atenção!", 
                    type:"alert-error errorCustom", 
                    text: response.message,
                    width: "100%",        		
		});		
	}
}

C7.getUrlCurrentAction = function(centerRequest) {
	ctrlAction = C7.action;
	
	if (centerRequest == false) {
		var ctrlAction = C7.toIfemCase(C7.action);
	}
	
	if ((this.settings.centerRequest && typeof centerRequest == 'undefined' ) || (centerRequest == true) || (centerRequest == "true")) {
		return './index.php?r='+C7.settings.currentModule+'/'+C7.settings.currentController+'/'+C7.settings.currentCenterMethod+'&action='+ctrlAction;
	} else {
		return './index.php?r='+C7.settings.currentModule+'/'+C7.settings.currentController+'/'+ctrlAction;
	}
}

C7.attachEventClick = function() {
	
	C7.currentForm.attachEvent("onButtonClick", function(action) {
	
		var validate = this.getUserData(action, 'validate');
		if ( validate !== false  ) {				
			if ( ! this.validate() ) { 
					return;
				}
		}
		
		C7.centerRequest = (this.getUserData(action, 'centerRequest') === false ? false: true);
		C7.actionType = (this.getUserData(action, 'actionType') || 'backend');
		C7.sendDatacustomCallback = this.getUserData(action, 'callback');
		params = (this.getUserData(action, 'params') || null);
		C7.currentForm = this;
		C7.ajaxOptions = this.getUserData(action, 'ajaxOptions');
		action = (this.getUserData(action, 'action') || action);
		var sendForm = (this.getUserData(action, 'sendForm') || true);
		
		var checkPermissions = (this.getUserData(action, 'checkPermissions') || 'false');
		
		if (typeof action != 'undefined' || typeof C7.action === 'undefined' || C7.action == '') {
			C7.action = action;
		} else {
			action = C7.action;
		}

		if (C7.actionType == 'client') {
    		C7.runActionC(C7.action, params, C7.sendDatacustomCallback, checkPermissions, validate );
		} else {
			C7.runActionB(C7.action, params, C7.sendDatacustomCallback, sendForm, checkPermissions, validate );
		}
				
		
	});
}

C7.attachMask = function(){
	C7.currentForm.forEachItem(function(name){
		// mascara numerica
		maskNumber = (C7.currentForm.getUserData(name, 'maskNumber') || false);
		if(maskNumber){
			C7.currentForm.inputMaskNumberMMS(name,(Number.isInteger(maskNumber[0])?maskNumber[0]:((Number.isInteger(maskNumber)?maskNumber:false))),(maskNumber[1] || false),(maskNumber[2] || false));
		}
	});
}

C7.beforeAttachEventSearch = function(){
	C7.centerRequest = false;
	C7.sendDatacustomCallback = 'callbackReload';				
}


C7.modalBoxImportFile = function(actionBackend, callbackImportFile, layoutFileName,  layoutBtnName, layoutPath) {
	
	if (actionBackend) {
		var layout = (layout || false),
			  antigoFormAtivo = C7.currentForm,
			  layoutFileName = (layoutFileName || false);
			  layoutBtnName = (layoutBtnName || "<?=Yii::t("app",'Baixar Layout')?>"), 
			  layoutPath = (layoutBtnName || "files");
			  
			botoes = ["<?=Yii::t("app",'Importar')?>"];
			if (layoutFileName) {
				botoes.push(layoutBtnName);
			}
			botoes.push("<?=Yii::t("app",'Fechar')?>");
			
		var boxImportFile = dhtmlx.modalbox({
			title: C7.settings.titleModalboxImportFile,
			text: "<div id='formImportFile'></div>",
			buttons: botoes,
		});
		
		$('.dhtmlx_popup_button').on('click', function(){
			a = this.getAttribute('result');
			C7.actionImportFile(a);
			return false;
		});
		
		C7.actionImportFile = function(a) {
			if(a == 0){
				file = C7.currentForm.getItemValue('file');
				if(!file){
					dhtmlx.alert({
						text:"<?= Yii::t("app", "Selecione um arquivo para importar.") ?>",						
					});
					return false;
				} else {
					
					$.blockUI();
					C7.sendDatacustomCallback = callbackImportFile;
					C7.runActionBackend(actionBackend, false,  false, false, true);
					$.unblockUI();
					document.querySelectorAll('input[type="file"]')[0].value = '';
				}
			
			// btn layout
			} else if (layoutFileName && a == 1) {
				U.downloadURI(layoutPath + '/' + layoutFileName, layoutFileName);
				return false;
			}
			
			C7.currentForm = antigoFormAtivo;
			dhtmlx.modalbox.hide(boxImportFile);
			return false;
		}
		
		C7.getFormDataImportFile = function() {
			return [
				{type: "settings", position: "label-top", labelAlign: "left",  labelWidth: "auto"},
				{type: "block",  list: [
					{type:"file", name:"file", offsetLeft: 5, validate: 'Empty'},
				]},	
			];
		}
		
		// form dhtmlx
		C7.currentForm = new dhtmlXForm( 'formImportFile',  C7.getFormDataImportFile());
		
	}
}

/* TODO
 * Mover as funções abaixo para um arquivo útil 
 */
C7.getCellTextSelected = function(gridObj, colId) {
	return gridObj.cells(gridObj.getSelectedRowId() , gridObj.getColIndexById(colId)).getValue();
}

C7.getCellText = function(gridObj, rowId, colId) {
	return gridObj.cells(rowId , gridObj.getColIndexById(colId)).getValue();
}

C7.setCellVal = function(gridObj, colId, val) {
	gridObj.cells(gridObj.getSelectedRowId() , gridObj.getColIndexById(colId)).setValue(val);
}

C7.getHiddenRowsId = function(gridObj) {
	var rowsHidden = [];
	
	gridObj.forEachRow(function(id){
    	if (gridObj.getRowById(id).style.display == "none") {
            rowsHidden.push(id);    
        }	
    });

    return rowsHidden;
}

C7.totalRowsVisibleGrid = function(grid){
	var hiddenRows = C7.getHiddenRowsId(grid).length,
		totalRows = grid.getRowsNum() - hiddenRows;

	return totalRows;
}

C7.beforeSendMMS = function() {}

C7.settings = {
	titleWindowCreate: 'Adicionar Registro',
	titleWindowUpdate: 'Editar Registro',
	titleWindowDelete: 'Excluir Registro',
	titleModalboxImportFile: '<?= Yii::t('app','Importar arquivo')?>',
	subtitleWindow: '',
	centerRequest: true,
	currentModule: '',
	currentController: '',
	currentCenterMethod: '',
	actionReloadGrid: 'read',
	actionType: 'client',
	urlReloadGrid: '',
	gridReload: SYSTEM.Grid,
	formReloadGrid: 'main',
	params : null,
    sendForm : true,
    async : true,
    params :'',
    sendDatacustomCallback: '',
    checkPermissions: false,
    validate: true
};

C7.capitalise = function (string) {
   return string.charAt(0).toUpperCase() + string.slice(1);
}

C7.toIfemCase = function (s) {
	return s.replace(/\.?([A-Z]+)/g, function (x,y){return "-" + y.toLowerCase()}).replace(/^-/, "");
}

C7.alertAtencao = function (message){
	dhtmlx.alert({
		title:"<?= Yii::t("app", "Atenção!")?>", 
		type:"alert-error errorCustom", 
		text: message,
		width: "100%",        		
	});	
}

C7.exportGridToCSV = function(gridName) {
   var btn = [{
            action: 'ExportGridToCSVGlobal',
            title: 'Exportar CSV',
            icon: 'excel'
        }];
    C7.setGridBtns(C7.grid[gridName], btn, C7.grid[gridName].layout, gridName);
};

C7.actionExportGridToCSVGlobal = function (gridName, skipCol){
    C7.grid[gridName].enableCSVHeader(true);
    C7.grid[gridName].setCSVDelimiter(';');
    skipColCSV = function(csv, skipCol) {return csv;};
    var filename = 'relatorio.csv',
        csvFile = C7.grid[gridName].serializeToCSV(true),
        csvFileSkip = (skipCol ? skipColCSV(csvFile, skipCol) : csvFile),
        blob = new Blob([csvFileSkip], { type: 'text/csv;charset=utf-8;' });

    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

};


</script>

