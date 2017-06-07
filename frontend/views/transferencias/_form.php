<script type="text/javascript" charset="utf-8">


Form._init = function(conf) {
	$.extend(this.settings, conf);

	SYSTEM.boot();

	SYSTEM.Toolbar.titulo(this.settings.titleWindowMain);

	SYSTEM.Toolbar.setIconesAcoes([{
		 "adicionar":"globalCreate",
		 "atualizar":"refresh",
	}]);	

			
	Form.load('Toolbar', 'Main');	
	Form.load('Window', 'Main');
	Form.load('Grid', 'Main', SYSTEM.Layout.tela, '', true);	
	
	SYSTEM.Toolbar.core.attachEvent("onClick", Form.Toolbar);
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

Form.getFormDataCrud = function() {
   
       return [
				 {type: "settings", position: "label-left", labelAlign: "right", labelWidth: "AUTO", inputWidth: 200, offsetTop: 1},
               	 {type:"block",  label: this.settings.subtitleWindow, list:[
                                     {type:"input",  name:"PAG04_ID_TRANSACAO", label:"<?= $al["PAG04_ID_TRANSACAO"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_COD_TRANS_ADQ", label:"<?= $al["PAG04_COD_TRANS_ADQ"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_VLR_TRANS", label:"<?= $al["PAG04_VLR_TRANS"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_VLR_TRANS_LIQ", label:"<?= $al["PAG04_VLR_TRANS_LIQ"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_VLR_EMPRESA", label:"<?= $al["PAG04_VLR_EMPRESA"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_VLR_CLIENTE", label:"<?= $al["PAG04_VLR_CLIENTE"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_VLR_ADMIN", label:"<?= $al["PAG04_VLR_ADMIN"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_PREV_DEP_CONTA_BANC_MASTER", label:"<?= $al["PAG04_DT_PREV_DEP_CONTA_BANC_MASTER"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_DEP_CONTA_BANC_MASTER", label:"<?= $al["PAG04_DT_DEP_CONTA_BANC_MASTER"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER", label:"<?= $al["PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_DEP_CONTA_VIRTUAL_MASTER", label:"<?= $al["PAG04_DT_DEP_CONTA_VIRTUAL_MASTER"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL", label:"<?= $al["PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL"] ?>", inputWidth: 289,}, 
                    {type:"input",  name:"PAG04_DT_DEP_SUBCONTA_VIRTUAL", label:"<?= $al["PAG04_DT_DEP_SUBCONTA_VIRTUAL"] ?>", inputWidth: 289,}, 
                    					{type: "button", name:"globalSave", value: "Salvar", className: "buttom-window-right"}
       			 ]}
	   ];
}




</script>
