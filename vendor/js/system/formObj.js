var Form = function() {
	this.centerRequest = true;
	this.currentModule = '';
	this.currentController = '';
	this.currentCenterMethod = '';
	this.action = '';
};

Form.crud = {};
Form.crud.modal = {};
Form.FilterA = {};
Form.formOpen = true;

Form._init = function(conf) {
	$.extend(this.settings, conf);
}

Form.crud.modal.init = function() {
	
	SYSTEM.Windows = new dhtmlXWindows();
	
    SYSTEM.Windows.createWindow("actions", 0,0,325, 366);
    
    SYSTEM.Windows.window("actions").button('minmax1').hide();
    SYSTEM.Windows.window("actions").button('park').hide();
    SYSTEM.Windows.window("actions").denyResize();
    SYSTEM.Windows.window("actions").center();    
    SYSTEM.Windows.window("actions").hide();
	
    SYSTEM.Windows.window("actions").attachEvent("onClose", function(win){
    	Form.close();
    });
}

Form.crud.init = function() {
	
	var formData = Form.getFormData('Crud');  
	
    Form.crud = SYSTEM.Windows.window("actions").attachForm(formData, true);          
    Form.crud.bind(SYSTEM.Grid);
    
    Form.currentForm = Form.crud;
    
	Form.attachEventClick();
}

Form.FilterA.init = function() {
	
	var formData = Form.getFormData('FilterA');
	 
	Form.FilterA = SYSTEM.Layout.innerLayout.cells("a").attachForm();
	Form.FilterA.loadStruct(formData, 'json');
	Form.FilterA.setFocusOnFirstActive();

	Form.currentForm = Form.FilterA;
	
	Form.attachEventClick();
}  	
 

Form.Toolbar = function(itemId) {

	var methodPrefix = 'action';
	var action = Form.capitalise(SYSTEM.Layout.icons[0].itemId);
	var errorMessage = 'Você precisa criar uma função nesse padrão '+ functionName +'() para a ação '+ itemID + ' funcionar.';
	
	var actionFnName =  methodPrefix + SYSTEM.Layout.icons[0].itemId;
	var beforeActionFnName = 'beforeAction' + action;
	var afterActionFnName = 'afterAction' + action;	
	
	/* Seta a action atual com a action definida ao chamar a função 
	* SYSTEM.Toolbar.setIconesAndActions([{"adicionar": "create"},{ "atualizar": "update"}]); 
	* ou seja se o usuario clicar no botão 'adicionar' na toolbar, na linha abaixo 
	* é definido a action atual como 'create'. 
	* A action atual é usada para fazer um ajax para o backend. Ex:
	* Se o Form.action == 'create', ao executar a função Form.executeAction será disparado uma 
	* requisição para o método create do controller atual.
	*/	
	Form.action = SYSTEM.Layout.icons[0].itemId;	
	
	
	// Executa o evento BeforeAction
	Form.callFunctionDynamically(beforeActionFnName, '');
	
	
	/* Executa a action vinculada ao icone da toolbar, ela é definida ao chamar essa função:
	* SYSTEM.Toolbar.setIconesAndActions([{"adicionar": "create"},{ "atualizar": "update"}]); 
	* geralmente ela é chamada na view index.php */	
	Form.callFunctionDynamically(actionFnName, errorMessage);
	
		
	// Executa o evento After Action
	Form.callFunctionDynamically(afterActionFnName, '');
}

Form.close = function() {
	SYSTEM.Windows.window("actions").hide();
	SYSTEM.Windows.window("actions").setModal(false);
}

Form.show = function() {
	 SYSTEM.Windows.window("actions").show();
    SYSTEM.Windows.window("actions").setModal(true);
}

Form.reloadGrid = function() {
	Form.sendDatacustomCallback = 'callbackReload';	
		
	Form.currentForm = Form.FilterA;
	Form.action = 'search';	
	Form.executeAction();
}


/**
* 
* Essa função foi criada para conter a definição dos valores padrões para os elementos do formulário 
* e deve ser usada sempre após o bind do formulário (Form.crud.bind(SYSTEM.Grid)) pois o bind remove os
* valores padrões.
* 
* Ela deve apresentar métodos como esse:
	
* Form.crud.setItemValue('NAME_DO_CAMPO', 'VALOR_PADRÃO');
* 
**/
Form.setDefaultValuesFields = function() {
	
}


Form.actionCreate = function(){
	Form.currentForm = Form.crud;
	Form.sendDatacustomCallback = '';	
	
	Form.crud.clear();
	Form.setDefaultValuesFields(); 	
	SYSTEM.Windows.window("actions").setText(this.settings.titleWindowCreate);
	Form.show();
	
}

Form.actionUpdate = function(){
	Form.currentForm = Form.crud;
	Form.sendDatacustomCallback = '';	
	
	Form.setDefaultValuesFields();
	SYSTEM.Windows.window("actions").setText(this.settings.titleWindowUpdate);
	Form.show();
}

Form.actionDelete = function(){	
	Form.currentForm = Form.crud;
	Form.sendDatacustomCallback = '';	
	
	Form.setDefaultValuesFields();
	SYSTEM.Windows.window("actions").setText(this.settings.titleWindowUpdate);
	Form.show();
}

Form.executeAction = function () {	
	var url = Form.getUrlCurrentAction();		
	Form.sendData(url);	
}

Form.getFormData = function(typeForm) {
	methodPrefix = 'getFormData';
	functionName = methodPrefix + typeForm
	
	if (typeof Form[functionName] !== 'function') {
		console.log('Erro: Você deve criar uma função nesse formato getFormData'+functionName+'() retornando o json do formulário.')
		return false;
	}   	

	return Form[functionName]();
}

Form.callFunctionDynamically = function(functionName, errorMessage) {
	
	if (typeof Form[functionName] !== 'function') {
		console.log(errorMessage);
		return false;
	}   	

	return Form[functionName]();
}

Form.sendData = function(urlSend) {
	if (Form.sendDatacustomCallback == '') {
    	Form.currentForm.send(urlSend, "post", Form.sendDataCallbackDefault);
	} else {
		Form.currentForm.send(urlSend, "post", Form[Form.sendDatacustomCallback]);
	}
}

Form.callbackReload = function (loader, response) {	
	SYSTEM.Grid.clearAll(true);
	SYSTEM.Grid.loadXMLString(response);
}


Form.sendDataCallbackDefault = function(loader, response) {	
	response = JSON.parse(response);	
	if (response.status) {			
		 Form.reloadGrid();
		
		 if (response.message === 'undefined' || response.message == '') {
				response.message = "Operação realizada com sucesso!";
			 }
			
		 dhtmlx.alert({text: response.message , ok: "ok"});

		 if (Form.formOpen) {
		 	Form.close();
		 }
	} else {
		
		if (response.message === 'undefined' || response.message == '') {
			response.message = "Erro ao realizar a operação.";
		}
		
		dhtmlx.alert({
    		title:"Atenção!", 
    		type:"alert-error errorCustom", 
    		text: response.message,        		
		});		
	}
}




Form.getUrlCurrentAction = function() {
	if (Form.centerRequest) {
		return './index.php?c=<?= $this->seg()->urlEncode("'+Form.currentModule+'/'+Form.currentController+'/'+Form.currentCenterMethod+'&action='+Form.action+'")?>';
	} else {
		return './index.php?c=<?= $this->seg()->urlEncode("'+Form.currentModule+'/'+Form.currentController+'/'+Form.action+'")?>';
	}
}

Form.attachEventClick = function() {
	
	Form.currentForm.attachEvent("onButtonClick", function(id) {		
						
			switch (id) {		
    			case 'search' :				
    				Form.currentForm = Form.FilterA;
    				Form.action = 'search';
					Form.centerRequest = false;
    				Form.sendDatacustomCallback = 'callbackReload';				
    			break;
			}		  
			Form.executeAction();
	});
}


Form.settings = {
	titleWindowCreate: 'Adicionar Registro',
	titleWindowUpdate: 'Editar Registro',
	titleWindowDelete: 'Excluir Registro',
	subtitleWindow: '',
	centerRequest: true,
	currentModule: '',
	currentController: '',
	currentCenterMethod: '',
	
};

Form.capitalise = function (string) {
   return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}