<script type="text/javascript" charset="utf-8">


Form._init = function(conf) {
	$.extend(this.settings, conf);

	SYSTEM.boot();

	SYSTEM.Toolbar.titulo(this.settings.titleWindowMain);

	SYSTEM.Toolbar.setIconesAcoes([{
		 "adicionar":"globalCreate",
		 "atualizar":"refresh",
	}]);	

	<?php if ($generator->dhtmlxLayout === 'gridLayout') : ?>
		SYSTEM.Layout.t3('Listagem');
	<?php endif; ?>
	<?php if ($generator->dhtmlxLayout === 'basicLayout') : ?>
		SYSTEM.Layout.t1('Listagem');
    <?php endif; ?>
	
	Form.load('Toolbar', 'Main');	
	Form.load('Window', 'Main');
	Form.load('Grid', 'Main', SYSTEM.Layout.tela, '', true);	<?php if ($generator->dhtmlxLayout === 'gridLayout') : ?>
	Form.load('Form', 'FilterA', SYSTEM.Layout.telaCima, '', true);	
    <?php endif; ?>

	
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

<?php if (!empty($generator->columnsTypeJs)) : ?>
<?php foreach ($generator->columnsTypeJs as $keyForm => $columnsTypeJs) { ?>
Form.getFormData<?= $keyForm ?> = function() {
   <?= $generator->getTextVarsComboForm($keyForm); ?>

       return [
				 {type: "settings", position: "label-left", labelAlign: "right", labelWidth: "AUTO", inputWidth: 200, offsetTop: 1},
               	 {type:"block",  label: this.settings.subtitleWindow, list:[
                 <?php foreach ($columnsTypeJs as $k => $component) { ?>
                    <?php foreach ($component as $k2 => $js) { ?>
<?= $component[$k2]['obj']." \n" ?>
                    <?php }}?>
					{type: "button", name:"<?php echo ($keyForm == 'Crud') ? 'globalSave': 'search'; ?>", value: "<?php echo ($keyForm == 'Crud') ? 'Salvar': 'Filtrar'; ?>", className: "buttom-window-right"}
       			 ]}
	   ];
}


<?php }?>
<?php endif;?>


</script>
