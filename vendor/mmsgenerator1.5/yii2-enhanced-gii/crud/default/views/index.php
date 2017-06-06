<div id="layout" style="width:auto; height:800px;"></div>
<script type="text/javascript" charset="utf-8">

	SYSTEM.boot();
	
	formConf = {
			titleWindowDelete:<?='"<?='?> Yii::t("app", 'Remover Registro') <?='?>"'?>,
    		titleWindowMain: <?='"<?='?> Yii::t("app", $tituloTela) <?='?>"'?>,    		
    		currentCenterMethod: "global-crud",
    		currentModule: "<?= $generator->module ?>",
    		currentController: '<?= $generator->controllerNameUrl ?>',
			actionReloadGrid: 'Main',
			gridReload: 'Main',
			urlReloadGrid: './index.php?c=<?='<?= $this->seg()->urlEncode("'.$generator->module. '/'. $generator->controllerNameUrl .'/global-read&gridName=Main")?>'?>',
			urlLoadGridPrefix: './index.php?r=<?='<?= "'.$generator->module. '/'. $generator->controllerNameUrl .'/global-read&gridName="?>'?>',
			callbackUrlReloadGrid: SYSTEM.callbackReloadGrid,
			titleWindowCreate: <?='"<?='?> Yii::t("app", "Adicionar Registro") <?='?>"'?>,
    		titleWindowUpdate: <?='"<?='?> Yii::t("app", "Editar Registro") <?='?>"'?>,
    		titleWindowDelete: <?='"<?='?> Yii::t("app", "Excluir Registro") <?='?>"'?>,
			toolbarTitle: <?='"<?='?> Yii::t("app", $tituloTela) <?='?>"'?>,
    		subtitleWindow: '',
    	}; 

	Form._init(formConf);
	
</script>
