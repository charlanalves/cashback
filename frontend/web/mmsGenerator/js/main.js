$(window).load(function(){	
	idGenerator = $('#idGenerator').val();
	SqlBuilder.idGenerator = idGenerator;
	
	$('#query-genenerator').on('click','#btn-qg', function(){
		var sqlObj = SqlBuilder.getSql()
		
		SqlBuilder.queryClean()
		
		SqlBuilder.setQueryDom(sqlObj);
	});
	
	$('#generator-filedprimarykey').focusin(function(){
		
		var tableName = $('#generator-tablename').val();
		
		if (tableName != '') {
			var columns = SqlBuilder.getColBytable(tableName);
			var selectId = '#generator-filedprimarykey';
				
			SqlBuilder.reloadSelectOptions(selectId, columns);
		}
		
	})
	
	$('#preview-generate, #generatebtn').click(function(){
		sqlObj = SqlBuilder.getSql() ;
		
		sql = [];
		
		$.each(sqlObj, function(k, v){
			sql.push(v.data)
		});
		
		var columnsArray = SqlBuilder.getColumnsObj();
		var checkBoxFiles = $("input[type='checkbox'].answers").serializeArray();
		
		var form = $('#'+ idGenerator +'-generator').serializeArray()
		
		form.push({name: 'query', value: sql},{name:'columnsArray', value: columnsArray});
		
		if (typeof window.gridCrud !== 'undefined' && typeof window.gridFilter !== 'undefined') {
			
			var formSettingsCrud = UtilDhtmlxComponents.getSettingFields(window.gridCrud);
			var formSettingsFilter = UtilDhtmlxComponents.getSettingFields(window.gridFilter);			
			
			form.push({name: 'formSettings', value: {'Crud' : formSettingsCrud, 'FilterA': formSettingsFilter} })	
		} else if(typeof window.gridCrud !== 'undefined') {
			
			var formSettingsCrud = UtilDhtmlxComponents.getSettingFields(window.gridCrud);
			form.push({name: 'formSettings', value: {'Crud' : formSettingsCrud}})
		} else if (typeof window.gridFilter !== 'undefined') {
		
			var formSettingsFilter = UtilDhtmlxComponents.getSettingFields(window.gridFilter);
			form.push({name: 'formSettings', value: {'FilterA': formSettingsFilter}})
		} else {
			return false;
		}
		
		var url = 'index.php?r=gii/default/view2&id='+idGenerator;
		$.ajax({
	          type: 'POST',
	          async: false,
	          url: url,
	          dataType: "json",
	          data: {
	        	  	generator: form,
	        	  	answers: checkBoxFiles,
	        	  },
	        	  
		          success: function (data) {
		        	  $('#generate-result').html(data.html);
		        	  if (data.preview) {
			        	  	$('#generatebtn').show();
			        	  	$('#preview-generate').hide();
		        	  } else {
		        			$('#generatebtn').hide();
			        	  	$('#preview-generate').show();
		        	  }
		          },
		          error: function (XMLHttpRequest, textStatus, errorThrown) {
		                
		          }
	      });
	     return false;
		
	});
	
	
	//gridSF = MainHtmlComponents.renderGridSettingFields('grid-setting-fields');
	
	$('#btn-refresh-grid-form-crud').click(function() {	
		var url = './index.php?r=gii/default/grid&id='+ idGenerator;
		sqlObj = SqlBuilder.sendFormCrud(url);
	 });


	$('#btn-refresh-grid-form-filter').click(function() {
		var url = './index.php?r=gii/default/grid&id'+ idGenerator;
		sqlObj = SqlBuilder.sendFormFilter(url);
	 });
	
	
	$('#generator-dhtmlxlayout').change(function() {		
			switch (this.value) {
				case 'basicLayout':
					$('#fieldsConfigFilter').hide();
				break;
				case 'gridLayout':
					$('#fieldsConfigFilter').show();
				break;	
			}
	 });
	
	
	
	$('#generator-onetable').click(function() {
			var oneTbCheck = $('#generator-onetable:checked').val();
			if (oneTbCheck) {
				$('#relacoes .btn-group.pull-right.group-actions ').hide()
				$('#relacoes button.btn.btn-xs.btn-danger').hide()
				$('#relacoes .btn-group.bootstrap-select.s.width95.joinClause').hide()
				$('button.btn.btn-xs.btn-primary.dim').hide()
				$('button[data-id="rightTbrelacoes_rule_0"]').hide()
				$('button[data-id="rightColrelacoes_rule_0"]').hide() 
				$('button[data-id="rightColSelrelacoes_rule_0"]').hide()
				$('button[data-id="leftColrelacoes_rule_0"]').hide() 
			} else {
				$('#relacoes .btn-group.pull-right.group-actions ').show()
				$('#relacoes button.btn.btn-xs.btn-danger').show()
				$('#relacoes .btn-group.bootstrap-select.s.width95.joinClause').show()
				$('button.btn.btn-xs.btn-primary.dim').show()
				$('button[data-id="rightTbrelacoes_rule_0"]').show()
				$('button[data-id="rightColrelacoes_rule_0"]').show() 
				$('button[data-id="rightColSelrelacoes_rule_0"]').show() 
				$('button[data-id="leftColrelacoes_rule_0"]').show()
			}
	 });
})