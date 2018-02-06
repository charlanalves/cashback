<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "VIEWREPRESENTANTE",
            currentController: 'administrador',
            actionReloadGrid: 'RepresentantesMain',
            gridReload: 'RepresentantesMain',
            urlReloadGrid: './index.php?r=administrador/global-read&gridName=RepresentantesMain',
            urlLoadGridPrefix: './index.php?r=administrador/global-read&gridName=',
            titleGridRepresentantesMain: 'Representantes',
         }; 

        C7.init = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>