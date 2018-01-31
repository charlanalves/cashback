<script type="text/javascript" charset="utf-8">


C7.init = function(conf){
    $.extend(this.settings, conf);
    
    dhtmlx.image_path = "./dxassets/dhtmlx/terrace/imgs/";
    C7.load('Tab', 'Principal');
    C7.load('Toolbar', 'Main');
    
   //---------------------Tab Conta Virtual-------------------------
    C7.load('Tab', 'TransVirtual');
    C7.load('Grid', 'Main', layoutVirtual.cells('a'));
    
   //---------------------Tab Conta Virtual-------------------------
   
  
}
C7.setDefaultValuesFields = function(){}
C7.actionDoTransfer = function(){	
    var idRow = C7.grid.Main.getSelectedRowId(); 
    C7.sendForm = false;
    C7.runActionB('realizaSaques',{id: idRow});
}

C7.setToolbarGridVencerHoje = function(){
	return [
	        {action:'FazerTodasTrans', title: 'Fazer Transferências',icon:'transfer'}
    ];
}

C7.actionFazerTodasTrans = function(){
	C7.runActionB('FazerTodasTrans');
}

C7.getTabPrincipal = function(){
    tabPrincipal = new dhtmlXTabBar({parent: "tabbarObj"});
    tabPrincipal.addTab('principal','Transferencias');
    tabPrincipal.setTabActive('principal');
}

C7.getToolbarMain = function() {
        SYSTEM.Toolbar =  SYSTEM.loadToolbar();
        SYSTEM.Toolbar.setIconesAcoes([{
            adicionar:'adicionar'
        }]);
        SYSTEM.Toolbar.titulo('Gerenciamento das Transferências');
	//SYSTEM.Toolbar.titulo(this.settings.toolbarTitle);
	SYSTEM.Toolbar.setIconesAcoes([this.settings.toolbarBtn]);
	SYSTEM.Toolbar.core.attachEvent("onClick", C7.Toolbar);
}


C7.getTabTransVirtual = function(){
    tabsInternas = tabPrincipal.cells('principal').attachTabbar();
    tabsInternas.addTab('virtual','Pendentes',"300px");
    tabsInternas.setTabActive('virtual');
    
    layoutVirtual = tabsInternas.cells('virtual').attachLayout('1C');
   
    
}



</script>
