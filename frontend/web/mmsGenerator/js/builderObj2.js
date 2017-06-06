var QueryBuilder = function($el, options) {
    this.init($el, options);
};
	getParms = function (params)
	{
		if (typeof params === 'undefined' || params == '') {
			return '';
		}
		
		return parms.join(',');
	}
	
	getAvailableStatments = function ()
	{
		return [{
				    id: 'columns',
					callback: getColumns,
					params: ['SELECT ']
				
				},
			
				{	
					id: 'from',
					callback: getFromClause,
					params:['FROM ']
				},
			
				{
					id: 'joins',
					callback: getJoinsStatment
				},
				
				{
					id: 'where',
					callback: getWhereClause,
					params:	['WHERE ', '#conditions']
				},	
				
			];
	}
	
	getSql = function ()
	{	
		var stms = getAvailableStatments();
		
		$.each(stms, function(k, stm){
			
			window
		});
		
		
		var columns = [];
		var from = '';
		var joins = '';
		var where ='';
		var select =''
		var query = [];
			
		columns = getColumns();
		select = 'SELECT '+columns;
		from = 'FROM '+getFromClause();
		joins = getJoinsStatment();
		where = 'WHERE '+getWhereClause('#conditions');
		
		query.push(select, from, joins, where);
	}
	

	queryClean = function ()
	{
		$('#query-genenerator code.select').html('');
		$('#query-genenerator div.row.joins').html('');
		$('#query-genenerator code.where').html('');
	}
	
	getJoinsStatment = function ()
	{
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
	
	getColumns = function ()
	{
		columns = [];
	
		$('select.selectClause option:selected').each(function(){
			if ($(this).val() != 0) {    
				columns.push($(this).attr('table')+'.'+$(this).text());
			}
		});
		
		return columns.join();
	}
	
	getFromClause = function ()
	{
		return $('select.fromClause option:selected').val();
	}
	
	getWhereClause = function (selectorGroup)
	{
		return $(selectorGroup).queryBuilder('getSQL').sql;
	}
	
	setQueryDom = function (select, from, joins, where)
	{
		//select
		$('#query-genenerator code.select').html(select);
		
		//joins
		$.each(joins, function(k, join){
			joinHtml = '<div class="row margin">'+		
						  '<code class="joins margin">'+join+'</code>'+
					  '</div>';
			$('#query-genenerator div.row.joins').append(joinHtml);
		});
		
		//from
		$('#query-genenerator code.from').html(from);
		
		//where
		$('#query-genenerator code.where').html(where);
	}