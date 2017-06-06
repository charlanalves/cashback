/**
* BuilderObj
* Classe responsável por manipular o query builder do Gerador CRUD
*
* @access Public
* @author Charlan Santos
* @since  08/2016
*
**/
var SqlBuilder = function() {};

/**
 * Retorna os parametros separados por vírgula
 * @param {array}
 * @return {string}
 */
SqlBuilder.getParams = function (params)
{
	if (typeof params === 'undefined' || params == '') {
		return '';
	}
	
	return params.join(',');
}

/**
 * Retorna um objeto com os statments disponíveis para montagem da query 
 * @return {object}
 */
SqlBuilder.getAvailableStatments = function ()
{
	return [{
			    id: 'columns',
				callback: this.getColumns,
				params: ['SELECT ']
			
			},
		
			{	
				id: 'from',
				callback: this.getFromClause,
				params:['FROM ']
			},
		
			{
				id: 'joins',
				callback: this.getJoinsStatment
			},
			
			{
				id: 'where',
				callback: this.getWhereClause,
				params:	['WHERE ROWNUM < 1000 AND ', '#conditions']
			},	
			
		];
}

/**
 * Retorna os parametros separados por vírgula
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.getSql = function ()
{	
	var stms = this.getAvailableStatments(),	
	that = this;			
	
	$.each(stms, function(k, stm){
		stm.data = stm.callback.apply(that, stm.params);
	});
	
	return stms;
}
	
/**
 * Retorna os parametros separados por vírgula
 * @param {array}
 * @return {sting}
 */
SqlBuilder.queryClean = function ()
{
	$('#query-genenerator code.select').html('');
	$('#query-genenerator div.row.joins').html('');
	$('#query-genenerator code.where').html('');
}

/**
 * Retorna os parametros separados por vírgula
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.getJoinsStatment = function ()
{
	var oneTable = $('#generator-onetable').is(':checked');
	
	if (oneTable) {
		return '';
	}	
	
	var joins = [];
	$('select.joinClause option:selected').each(function(k,v){
		var joinClause = $(this).val();
		var leftTable = $(this).closest('li').find('select.tbp:eq(0)').val();
		var leftColumn = $(this).closest('li').find('select.col:eq(0) option:selected').text();
		var rightTable = $(this).closest('li').find('select.tbp:eq(1)').val();
		var rightColumn = $(this).closest('li').find('select.col:eq(1) option:selected').text();
		
		join = joinClause+' '+rightTable+' ON '+leftTable +'.'+leftColumn +' = '+rightTable+'.'+rightColumn;
		
		joins.push(join);
	});
	
	return joins;
	
}


/**
 * Retorna os parametros separados por vírgula
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.getDataRelatedByTable = function (table)
{
		if (table == '') {
			return false;
		}
		
		dataRelated = {};
		
		$('select.joinClause option:selected').each(function(k,v){
			
			var joinClause = $(this).val();
			var leftTable = $(this).closest('li').find('select.tbp:eq(0)').val();
			var leftColumn = $(this).closest('li').find('select.col:eq(0) option:selected').text();
			var rightTable = $(this).closest('li').find('select.tbp:eq(1)').val();
			var rightColumn = $(this).closest('li').find('select.col:eq(1) option:selected').text();
			
			if (table == leftTable) {
				dataRelated = {
					colJoin: leftColumn,
				    tbRelated: rightTable,
					colJoinRelated: rightColumn
				};
				
			} else if ((table == rightTable)) {
				dataRelated = {
					colJoin: rightColumn,
					tbRelated: leftTable,	
					colJoinRelated: leftColumn
				}
			}
		});
		
		return dataRelated;
}

/**
 * Retorna as colunas separadas por vírgula
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.getColumns = function (prefixText)
{
	columns = [];

	$('select.selectClause option:selected').each(function(){
	if ($(this).val() != 0) {    
		columns.push($(this).attr('table')+'.'+$(this).text());
		}
	});
	
	if (prefixText != '') {
		return prefixText + columns.join();
	}
		
	return columns.join();
}

/**
 * Retorna colunas
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.getTableAndColumns = function ()
{
	obj = [];


	$('select.selectClause option:selected').each(function(k,v){

		if ($(this).val() != 0) { 
			
			var table = $(this).attr('table');
			var column = $(this).text();
			
			obj.push({table, column})
		}

	});
		
	return obj;
}



SqlBuilder.getColumnsObj = function ()
{
	columns = [];

	$('select.selectClause option:selected').each(function(){
	if ($(this).val() != 0) {    
		columns.push($(this).text());
		}
	});
	
	
	return columns;
}

/**
 * Retorna a tabela da from clause
 * @param {string}
 * @return {sting}
 */	
SqlBuilder.getFromClause = function (prefixText)
{
	if (prefixText != '') {
		return prefixText + $('select.fromClause option:selected').val();
	}
	
	return $('select.fromClause option:selected').val();
}

/**
 * Retorna a clausula where completa em string
 * @param {string}
 * @param {string}
 * @return {sting}
 */
SqlBuilder.getWhereClause = function (prefixText, selectorGroup)
{
	if (selectorGroup == '') {
		return false;
	}
	
	var sql = $(selectorGroup).queryBuilder('getSQL').sql;
	
	if (sql == '') {
		return '';
	}
	
	if (prefixText != '') {
		return prefixText + sql;
	}
	
}

/**
 * Seta um elemento do dom com a query
 * @param {array}
 * @return {sting}
 */	
SqlBuilder.setQueryDom = function (queryObj)
{
	$.each(queryObj, function(k, stm) {
		switch (stm.id) {
			case 'columns':
				$('#query-genenerator code.select').html(stm.data);
			break;
				
			case 'from':
				$('#query-genenerator code.from').html(stm.data);
			break;
				
			case 'joins':
				if (stm.data !== '') {
					$.each(stm.data, function(k, join){
						
						joinHtml = '<div class="row margin">'+		
						'<code class="joins margin">'+join+'</code>'+
						'</div>';
						
						$('#query-genenerator div.row.joins').append(joinHtml);
						
					});					
				}
				
			break;
			case 'where':
				$('#query-genenerator code.where').html(stm.data);
			break;
		}
	});
}


/**
 * Envia os dados do formulário do gerador para gerar os arquivos do crud
 * @param {array} - array de dados a serem enviados
 * @return {void}
 */	
SqlBuilder.sendGeneratorData = function (data)
{
	
	var url = "index.php?r=gii/default/view2&id=" + SqlBuilder.idGenerator + "&teste=1",
	jqxhr;
	
	jqxhr = $.ajax({
		type: 'POST', async: false, url: url, data:data, 
		success: this.setDataHtml(data, '#finish-step #generate-result'), 
		dataType: 'html'
	});
	
	return false;
}


/**
 * Executa a função $.html() com os dados informados nos parametros
 * @param {mixed} - dados a serem inseridos no dom
 * @param {string} - seletor do elemento
 * @return {void}
 */	 
SqlBuilder.setDataHtml = function (data, selector)
{
	if (selector == '' || data == '') {
		return false;
	}
	 
	$(selector).html(data);
}


SqlBuilder.sendFormCrud = function (url)
{
	if (typeof  window.gridCrud === 'undefined') {
		window.gridCrud = new dhtmlXGridObject('grid-form-crud');
	} 

	var tablesAndCol = SqlBuilder.getTableAndColumns();


	window.gridCrud.setImagePath("../libs/dhtmlx/terrace/imgs/");
	
	window.gridCrud.enableDragAndDrop(true);

	window.gridCrud.init();


	$.ajax({type: 'POST', url: url, dataType: "xml", data: {data: tablesAndCol}, success: function (datar) {
		window.gridCrud.clearAll(true);
		window.gridCrud.parse(datar);	
		}
	});
	
	this.registerEventOnchange(window.gridCrud);
}

SqlBuilder.sendFormFilter = function (url)
{
	if (typeof  window.gridFilter === 'undefined') {
		window.gridFilter = new dhtmlXGridObject('grid-form-filter');
	} 

	var tablesAndCol = SqlBuilder.getTableAndColumns();


	window.gridFilter.setImagePath("../libs/dhtmlx/terrace/imgs/");
	
	window.gridFilter.enableDragAndDrop(true);
	
	window.gridFilter.init();


	$.ajax({type: 'POST', url: url, dataType: "xml", data: {data: tablesAndCol}, success: function (datar) {
		window.gridFilter.clearAll(true);
		window.gridFilter.parse(datar);	
		}
	});
	
	this.registerEventOnchange(window.gridFilter);
}

SqlBuilder.registerEventOnchange = function (dhtmlxGrid)
{
	/* 
	* Caso seja necessario exibir um modal para configurar cada tipo de dados
	* como autocomplete, combo e etc. Implementar a função abaixo para que no 
	* onchange dos tipos exiba o modal com as configurações.
	* 
	
		that = this;
		
		dhtmlxGrid.attachEvent("onEditCell", function(stage, rId, cIndex, nValue, oValue) {
			
			if (stage == 2) {
				
				that.modalForm(nValue);
			}
			
			return true;
		});
	*/
	
	return true;
}


SqlBuilder.modalForm = function (nValue)
{
	var formData = this.getFormData(nValue);   
	
	if (formData === false) {
		return;
	}
	
	window.w = new dhtmlXWindows();
	window.w.createWindow("formSettings", 0,0,325, 366);
	
	window.Form = window.w.window("formSettings").attachForm(formData, true);
	window.w.window("formSettings").setText('CONFIGURAR TIPO DO CAMPO');
	window.w.window("formSettings").button('minmax1').hide();
	window.w.window("formSettings").button('park').hide();
	window.w.window("formSettings").denyResize();
	window.w.window("formSettings").center();	
	
	
	window.w.window("formSettings").attachEvent("onClose", function(win){
		window.w.window("formSettings").hide();
		window.w.window("formSettings").setModal(false);
	});
	
	
	//this.registerSaveEvent();
}




SqlBuilder.ucfirst = function(str) {
	  str += ''
	  var f = str.charAt(0)
	    .toUpperCase()
	  return f + str.substr(1)
}

SqlBuilder.getFormData = function(typeForm) {
	
	methodPrefix = 'getFormData';	
	functionName = methodPrefix + this.ucfirst(typeForm)
	
	if (typeof this[functionName] !== 'function') {
		/*
		* Você deve criar uma função nesse formato getFormData'+functionName+'() retornando o json do formulário.
		* para que, ao clicar no tipo do campo no select apareça o formulario
		*/  
		return false;
	}   	

	return this[functionName]();
}

/**
 * Retona um array com o nome das colunas de uma tabela passada por parâmetro
 * @param {string} - tableName
 * @return {array}
 */	 
SqlBuilder.getColBytable = function(tableName){
	
	var columnsNames = '';
	var url = "index.php?r=gii/default/teste&id=" + SqlBuilder.idGenerator + "&method=getColumnNamesByTable&params="+tableName;
	
	 $.ajax({
		 url: url,
         type: "GET",
         dataType: "json",
         async: false, 
         success: function(data) { 
        	 columnsNames = data;
         }
	 });
	 
	 return columnsNames;
};

/**
 * Remove as opções atuais do select e carrega novas
 * @param {string} - O seletor do select
 * @param {array} - Options no formato [{0:{value:0, text:'opt1'}}]
 * 
 * @return {void}
 */	 
SqlBuilder.reloadSelectOptions = function(elSelector, options) {

	SqlBuilder.removeOptionsSelectExceptFirst(elSelector);
	
	$.each(options, function(k, col){
		if (typeof col.text !== 'undefined') {
			$(elSelector)
				.append('<option value="' + col.text + '">' + col.text + '</option>');
		}
	}) 

} 

/**
 * Retona um array com o nome das colunas de uma tabela passada por parâmetro
 * @param {string} - O seletor do select
 * @return {array}
 */	 
SqlBuilder.removeOptionsSelectExceptFirst = function(elSelector) {
	$(elSelector).find('option').not(':first').remove();
}






