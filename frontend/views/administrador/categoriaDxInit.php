<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "CB10CATEGORIA",
            currentController: 'administrador',
            actionReloadGrid: 'CategoriaMain',
            gridReload: 'CategoriaMain',
            urlReloadGrid: './index.php?r=administrador/global-read&gridName=CategoriaMain',
            urlLoadGridPrefix: './index.php?r=administrador/global-read&gridName=',
         }; 

        C7.init = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>