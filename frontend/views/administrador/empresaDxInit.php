<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "CB04EMPRESA",
            currentController: 'administrador',
            actionReloadGrid: 'EmpresasMain',
            gridReload: 'EmpresasMain',
            urlReloadGrid: './index.php?r=administrador/global-read&gridName=EmpresasMain',
            urlLoadGridPrefix: './index.php?r=administrador/global-read&gridName=',
            titleGridEmpresasMain: 'Empresas',
         }; 

        C7.init_empresa = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>