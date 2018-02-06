<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "VIEWFUNCIONARIO",
            currentController: 'estabelecimento',
            actionReloadGrid: 'FuncionariosMain',
            gridReload: 'FuncionariosMain',
            urlReloadGrid: './index.php?r=estabelecimento/global-read&gridName=FuncionariosMain',
            urlLoadGridPrefix: './index.php?r=estabelecimento/global-read&gridName=',
            titleGridFuncionariosMain: 'Funcionários',
         }; 

        C7.init = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>