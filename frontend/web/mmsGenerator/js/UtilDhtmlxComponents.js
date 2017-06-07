var UtilDhtmlxComponents = function() {};

UtilDhtmlxComponents.Windows = {};

dhtmlx.image_path = './preview/codebase/imgs/';


/**
 * Inicia e configura o grid da sessão configurações dos campos no MMS Generator
 * 
 * @param {elId} - id do elemento html que receberá o grid
 * @return {void}
 */
UtilDhtmlxComponents.renderGridSettingFields = function (elId)
{	
	var gridSF = new dhtmlXGridObject(elId);
	
	gridSF.setImagePath("../libs/dhtmlx/terrace/imgs/");
	gridSF.setHeader("Campos, Tipo do Campo");	
	gridSF.setInitWidths("250, 250");
	gridSF.setColAlign("left, left");
	
	gridSF.init();
	
	gridSF.makeSearch("searchFilter",1)
	
	return gridSF;
}

/**
 * 
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents.checkBoxOneTableIsChecked = function()
{
	return $('#generator-onetable:checked').val();	
}

/**
 * 
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents._getCustomDataCombo = function()
{

	if (this.checkBoxOneTableIsChecked()){
		this.value =  this.cname;		
	} else {
		var data = SqlBuilder.getDataRelatedByTable(this.table); 
		this.value = data.colJoin; 
		this.cname = data.colJoinRelated;
	}
}

/**
 * 
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents._getCustomDataCombo21 = function()
{
	this.cupdate = this.cname
	this.table = 'ECM21_REGISTRO_TABELA';
	this.value = 'ECM21_COD_REGISTRO';
	this.cname = 'ECM21_DESC_REGISTRO';
	this.where = 'ECM21_COD_TABELA = "17"';
}


/**
 * Para setar os parametros faça um objeto no formato abaixo: fieldType: {callback: functionName}
 * Ex: combo: {callback: 'teste'},
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents.getCustomData = {
		combo: {callback: '_getCustomDataCombo'},
		autocomplete: {callback: '_getCustomDataCombo'},
		comboEcm21: {callback: '_getCustomDataCombo21'}
}

/**
 * Para manipular dados de uma nova coluna no "grid de configuração dos campos" 
 * adicione um objeto conforme o exemplo
 * {
	id: 'typeFields',
	callback: 'getTypeFields'
  },
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents.getParamSettingFields = [
  {
	id: 'typeFields',
	callback: 'getTypeFields'
  },
  
 ];

/**
 * 
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents.getSettingFields = function(grid)
{
	that = this;
	this.grid = grid;
	settingsFields = [];
	
	$.each(this.getParamSettingFields, function(k, setting){
		var id = setting.id;
		var callback = that[setting.callback]();
		
		if (callback !== false) {
			settingsFields.push({ [id] : callback})
		}
	})
	
	return settingsFields;
}

/**
 * 
 * 
 * @param {} - 
 * @return {void}
 */
UtilDhtmlxComponents.getTypeFields = function ()
{	
	fields = [];
	that = this;
	var grid = this.grid;
	
	if (typeof grid !== 'undefined' ) {
	
		for (var i=1; i <= grid.getRowsNum(); i++) {
	
			this.typeField = grid.cells(i,2).getValue();
			this.table = grid.cells(i,0).getValue();
			this.cname = grid.cells(i,1).getValue();
			this.cupdate = '';
			this.where = '';
			this.label = grid.cells(i,3).getValue();
			this.column = grid.cells(i,1).getValue();
			this.value = '';
			this.params = {};		
			
			if (typeof that.getCustomData[that.typeField] !== 'undefined' 
				&& typeof that[that.getCustomData[that.typeField].callback] === 'function' ) {
				
				 that[that.getCustomData[that.typeField].callback]();
				
			}
			
		    fields.push({
		    	'type': this.typeField, 
		    	'table': this.table, 
		    	'name': this.cname,
		    	'label': this.label,
		    	'column': this.column,
		    	'value': this.value,
		    	'params': this.params,
		    	'cupdate': this.cupdate,
		    	'where': this.where
	    	});
		}
		
		return fields;
	}
}

UtilDhtmlxComponents.createWindow = function(name, options) {
	if (name == '' || typeof name === 'undefined' ) {
		return false;
	}
	
	config = {width:325, height: 366, titleWindow: 'Configuração do componente' };
	
	$.extend(config, options)
	
	UtilDhtmlxComponents.Windows = new dhtmlXWindows();
	
	UtilDhtmlxComponents.Windows.createWindow(name, 0,0,1100, 1100);
    
	UtilDhtmlxComponents.Windows.window(name).button('minmax1').hide();
	UtilDhtmlxComponents.Windows.window(name).button('park').hide();
	UtilDhtmlxComponents.Windows.window(name).denyResize();
	UtilDhtmlxComponents.Windows.window(name).center();    
	UtilDhtmlxComponents.Windows.window(name).hide();
	
	UtilDhtmlxComponents.Windows.window(name).setText(config.titleWindow);

	UtilDhtmlxComponents.Windows.window(name).attachEvent("onClose", function(win){
    	UtilDhtmlxComponents.Windows.close(name);
    });
}

UtilDhtmlxComponents.Windows.close = function(name) {
	UtilDhtmlxComponents.Windows.window(name).hide();
	UtilDhtmlxComponents.Windows.window(name).setModal(false);
}

UtilDhtmlxComponents.Windows.show = function(name) {
	UtilDhtmlxComponents.Windows.window(name).show();
	UtilDhtmlxComponents.Windows.window(name).setModal(true);
}


