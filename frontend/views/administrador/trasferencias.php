<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        
        SYSTEM.loadToolbar = function loadToolbar(){
		var toolbar = tabPrincipal.cells("principal").attachToolbar();
		toolbar.setIconsPath("./dxassets/layoutMask/imgs/");
		toolbar.loadXML("./dxassets/layoutMask/dhxtoolbar.xml?etc=" + new Date().getTime());                
		toolbar.attachEvent("onXLE", function(){
			toolbar.addSpacer("titulo");
			toolbar.forEachItem(function(itemId){
				toolbar.hideItem(itemId);
			});
		});
		return {
			core: toolbar,
			icones: function(iconsIds){
				setTimeout(function(){
					for(var i = 0; iconsIds.length > i ;i++){
						toolbar.showItem(iconsIds[i]);
					}
				}, 1000);
			},
			setIconesAcoes: function(iconsIds){
				setTimeout(function(){
                                    
					if (typeof iconsIds[0] != "undefined") {
						SYSTEM.Layout.icons = iconsIds;
						$.each(iconsIds[0], function(icon, action){
							toolbar.showItem(icon);
						});
					}
				}, 1000);
			},
			titulo: function (titulo){
                         setTimeout(function(){
					toolbar.showItem('titulo');
					toolbar.setItemText('titulo', titulo);
				}, 1000);
			}
		}
	}
       
        SYSTEM.Layout = {};
        
        
       var conf = {
                titleWindowDelete:"<?= Yii::t("app", 'Remover Registro') ?>",
    		currentCenterMethod: "global-crud",
    		currentModule: "admin",
    		currentController: 'transferencias',
                actionReloadGrid: 'Main',
                gridReload: 'Main',
                urlReloadGrid: './index.php?r=transferencias/global-read&gridName=Main',
                urlLoadGridPrefix: './index.php?r=transferencias/global-read&gridName=',
             //   callbackUrlReloadGrid: SYSTEM.callbackReloadGrid,
                titleWindowCreate: "<?= Yii::t("app", "Adicionar Registro") ?>",
    		titleWindowUpdate: "<?= Yii::t("app", "Editar Registro") ?>",
    		titleWindowDelete: "<?= Yii::t("app", "Excluir Registro") ?>",
    		subtitleWindow: '',
    	}; 

        Form.init(conf);
    });

</script>




<div id="tabbarObj" style="position: relative; width: 100%; height: 900px;"></div>


<style>
div#main-container {
    padding: 0px;
}

#main {    
    padding: 0px !important;
}
</style>