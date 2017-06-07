$(window).load(function(){	
	tableNames = '';
	getColBytable =''
	divRelacoes = $('#relacoes');
	divConditions = $('#conditions');
	idGenerator = $('#idGenerator').val();
	 
	 $.ajax({
         url: "index.php?r=gii/default/teste&id=" + idGenerator + "&method=getTableNameSelect",      
         type: "GET",
         dataType: "json",
         async: false,
         success: function(data) {   
        	 
        	tableNames = data; 
            
            getColBytable = function(tableName){
            	
            	var columnsNames = '';
            	 $.ajax({
            		 url: "index.php?r=gii/default/teste&id="+ idGenerator + "&method=getColumnNamesByTable&params="+tableName,
                     type: "GET",
                     dataType: "json",
                     async: false, 
                     success: function(data) { 
                    	 columnsNames = data;
                     }
            	 });
            	 
            	 return columnsNames;
            };
           
            divRelacoes.queryBuilder({ 
            	divMainId: 'relacoes',
            	filters: [{
            		id: 'COR04_fornecedores',
            	}],
            
            	relations: { fields: [
    	                      {id: 'leftTb', data: tableNames, type: 'select', text: 'Selecione a tabela:', htmlOptions: {'data-live-search': 'true', class: 'tbp fromClause'}},
    	                      {id: 'leftCol', parent: 'leftTb', callback: getColBytable, type: 'select', text: 'Selecione a coluna:', htmlOptions:{disabled: 'disabled', "data-live-search": 'true', class: 'col'}},
    	                      
    	                      {type: 'button', iconClass:'glyphicon glyphicon-log-out', text: 'ON:', htmlOptions:{class: 'btn btn-xs btn-primary dim'}},
    	                      
    	                      {id: 'join', data:[{value: 'join', text:'Join', htmlOptions:{selected: "true"}}, {value: 'left join', text:'Left Join'}, {value: 'right join', text:'Right Join'}] ,type: 'select', htmlOptions:{class: 'width95 joinClause'}},
    	                      {id: 'rightTb',data: tableNames, type: 'select', text: 'Selecione a tabela:', htmlOptions: {"data-live-search": 'true', class: 'tbp'}},
    	                      {id: 'rightCol', parent: 'rightTb', parentChild:'leftCol' ,callback: getColBytable, customRuleBlockField: true, type: 'select', text: 'Selecione a coluna:', htmlOptions:{disabled: 'disabled', "data-live-search": 'true', class: 'col'}},
    	                      
    	                      
    	                      {id: 'leftColSel', parent: 'leftTb', callback: getColBytable, type: 'select', htmlOptions:{class: "selectMultiple selectClause", disabled: 'disabled', multiple: "true", "data-actions-box":"true", "data-live-search": "true", title:"Colunas do Select:"}},
    	                      {id: 'rightColSel', parent: 'rightTb', callback: getColBytable, type: 'select', htmlOptions:{class: "selectMultiple selectClause margin8", disabled: 'disabled', multiple: "true", "data-actions-box":"true", "data-live-search": "true", title:"Colunas do Select:"}}, 
	                      ]},
            	lang_code: 'pt-BR',
            	allow_groups: false,
            	conditions: ['AND'],
            	placeholder: 'Selecione a tabela:',
            	
            	
            });
            
            //Editando o label padrão para o texto 'Nova relação' sem ter que alterar o arquivo de tradução
            divRelacoes.find("button[data-add='rule']").html('<i class="glyphicon glyphicon-plus-sign"></i> Nova Relação');
            divRelacoes.find(".group-conditions label").text('Construindo Relações:')


            
            divConditions.on('afterCreateRuleFilters.queryBuilder', function(e, rule, error, value) {
            	rule.$el.find('.rule-filter-container').prepend('<select id="'+e.builder.settings.ruleId+'_sel" class="tbc"><option value="0">Selecione a tabela</option></select>')            	
            	rule.$el.find('.rule-filter-container select')
            				.addClass('selectpickers')
            				.attr('data-live-search','true')
            				.selectpicker('render');
            		
            		e.builder._fillSelect();
            		rule.$el.closest('.query-builder').find('.form-control.selectpickers').selectpicker('refresh');
            		rule.$el.closest('.query-builder').find('.btn-group.bootstrap-select').css('width','200px');
				});
            
            
          var operators = [
              {
            	value: '=', 
            	text:'=', 
            	htmlOptions:{selected: "true"}
    		  },
              {
			    value:'<>',
			    text:'<>'
    		  },
    		  {
  			    value:'<',
  			    text:'<'
      		  },
      		{
  			    value:'>',
  			    text:'>'
      		  },
      		{
  			    value:'<=',
  			    text:'<='
      		  },
      		{
			   value:'>=',
			   text:'>='
    		  },
    		  {
  			    value:'Like "%{var}%"',
  			    text:'Like %Var%'
      		  },
	 		];
          
            divConditions.queryBuilder({ 
            	divMainId: 'conditions',
            	filters: [{
            		id: '-',
            		default_value: 'Selecione uma coluna',
            		
            	}],
            	select_placeholder:'Selecione uma coluna',
            	lang_code: 'pt-BR',            	
            	conditions: ['AND', 'OR'],
            	//placeholder: 'Selecione a tabela:',
            	callbackCustom: getColBytable,
            });
         

    		divConditions.on('change', 'select.tbc', function() {	
    			rule = [];
    			if ($(this).val() != 0) {
    				rule.id = $(this).closest('li').attr('id');
    				rule.groupid = $(this).closest('dl').attr('id');
    				rule.isGroup =  !$(this).closest('dl').is('#conditions_group_0');
    				rule.filter = false;    				
    				data = divConditions.queryBuilder('getSettings').callbackCustom($(this).val());
    				divConditions.queryBuilder('setFilters',true, data.nativeBuilder);
    			}
    		});

            
            
         },
         error: function(e) {
          
         }
	 });
			  
			
})


