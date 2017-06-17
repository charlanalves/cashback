<script type="text/javascript" charset="utf-8">


Form.init = function(conf){
    $.extend(this.settings, conf);
    
    dhtmlx.image_path = "./dxassets/dhtmlx/terrace/imgs/";
    Form.load('Tab', 'Principal');
    Form.load('Toolbar', 'Main');
    
   //---------------------Tab Conta Virtual-------------------------
    Form.load('Tab', 'TransVirtual');
    Form.load('Grid', 'Main', tabsInternas.cells('virtual'));
    
   //---------------------Tab Conta Virtual-------------------------
    Form.load('Tab', 'TransBancaria');
    Form.load('Grid', 'vencerHoje', layoutBancario.cells("a"));
    Form.load('Grid', 'vencer',  layoutBancario.cells("b"));
    Form.load('Grid', 'vencidas', layoutBancario.cells("c"));
  
}
        
Form.getTabPrincipal = function(){
    tabPrincipal = new dhtmlXTabBar({parent: "tabbarObj"});
    tabPrincipal.addTab('principal','Transferencias');
    tabPrincipal.setTabActive('principal');
}

Form.getToolbarMain = function() {
        SYSTEM.Toolbar =  SYSTEM.loadToolbar();
        SYSTEM.Toolbar.setIconesAcoes([{
            adicionar:'adicionar'
        }]);
        SYSTEM.Toolbar.titulo('Gerenciamento das Transferências');
	//SYSTEM.Toolbar.titulo(this.settings.toolbarTitle);
	SYSTEM.Toolbar.setIconesAcoes([this.settings.toolbarBtn]);
	SYSTEM.Toolbar.core.attachEvent("onClick", Form.Toolbar);
}


Form.getTabTransVirtual = function(){
    tabsInternas = tabPrincipal.cells('principal').attachTabbar();
    tabsInternas.addTab('virtual','Transferencias Conta Virtual Master',"300px");
    tabsInternas.setTabActive('virtual');
    
    layoutVirtual = tabsInternas.cells('virtual').attachLayout('1C');
   
    
}

Form.getTabTransBancaria = function(){
    tabsInternas.addTab('bancaria','Transferencias Cliente/Empresa',"300px");
    layoutBancario = tabsInternas.cells('bancaria').attachLayout('3E');
    layoutBancario.cells("a").setText("Transações a Vencer Hoje");
    layoutBancario.cells("b").setText("Transações a vencer");
    layoutBancario.cells("c").setText("Transações Vencidas");
}


</script>
