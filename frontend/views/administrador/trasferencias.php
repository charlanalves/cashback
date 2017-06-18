<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
         if (typeof C7 == 'undefined'){
				C7 = {};
		} 
        
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
       
      
      C7.layout = SYSTEM.Layout = {};
        
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
    		titleGridAgendadas: "<?= Yii::t("app", "Transferências Agendadas por Empresa") ?>",
    		titleGridVencerHoje: "<?= Yii::t("app", "Transferências a Vencer HOJE") ?>",
    		titleGridVencer: "<?= Yii::t("app", "Transferências a Vencer") ?>",
    		titleGridVencidas: "<?= Yii::t("app", "Transferências Vencidas") ?>",
    		subtitleWindow: '',
    	}; 

        C7.init(conf);
    });

</script>


<div class="row testett">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-desktop fa-fw "></i> Admin <span>&gt;
			Transferências </span></h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> My Income <span class="txt-color-blue">$47,171</span></h5>
				<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm"></div>
			</li>
			<li class="sparks-info">
				<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
				<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm"></div>
			</li>
			<li class="sparks-info">
				<h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
				<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm"></div>
			</li>
		</ul>
	</div>
</div>

<div id="tabbarObj" style="position: relative; width: 100%; height: 900px;"></div>


<style>
div#main-container {
    padding: 0px;
}

#main {    
    padding: 0px !important;
}
</style>