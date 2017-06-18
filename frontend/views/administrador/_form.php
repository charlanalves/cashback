<script type="text/javascript" charset="utf-8">


C7.init = function(conf){
    $.extend(this.settings, conf);
    
    dhtmlx.image_path = "./dxassets/dhtmlx/terrace/imgs/";
    C7.load('Tab', 'Principal');
    C7.load('Toolbar', 'Main');
    
   //---------------------Tab Conta Virtual-------------------------
    C7.load('Tab', 'TransVirtual');
    //C7.load('Grid', 'Main', tabsInternas.cells('virtual'));
    
   //---------------------Tab Conta Virtual-------------------------
    C7.load('Tab', 'TransBancaria');
    C7.load('Grid', 'Agendadas', layoutVirtual.cells("a"));
    
    C7.grid.Agendadas.attachEvent("onSubGridCreated", function(subgrid, rId){
 
    	 subgrid.setStyle("background-color:#3a3633;color:white; font-weight:bold;", "","color:red;", "");
          var url  = './index.php?r=transferencias/global-read&gridName=TAPedidosEmp&param='+ rId;
    	  subgrid.load(url, function(){
    	           subgrid.callEvent("onGridReconstructed",[]);
    	  });
   	});

    C7.grid.Agendadas.attachEvent("onSubGridLoaded", function(subgrid){
    	 subgrid.enableAutoHeight(true,100,true);
    	});

    C7.load('Grid', 'VencerHoje',  layoutBancario.cells("a"));
    C7.load('Grid', 'Vencer',  layoutBancario.cells("b"));
    C7.load('Grid', 'Vencidas', layoutBancario.cells("c"));

    
  
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
    tabsInternas.addTab('virtual','Agendadas',"300px");
    tabsInternas.setTabActive('virtual');
    
    layoutVirtual = tabsInternas.cells('virtual').attachLayout('1C');
   
    
}

C7.getTabTransBancaria = function(){
    tabsInternas.addTab('bancaria','Pendentes',"300px");
    layoutBancario = tabsInternas.cells('bancaria').attachLayout('3E');
    layoutBancario.cells("a").setText("Transações a Vencer Hoje");
    layoutBancario.cells("b").setText("Transações a vencer");
    layoutBancario.cells("c").setText("Transações Vencidas");
}


</script>
