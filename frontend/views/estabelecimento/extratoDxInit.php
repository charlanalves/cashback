<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "EstabelecimentoExtratoModel",
            currentController: 'estabelecimento',
            actionReloadGrid: 'Main',
            gridReload: 'Main',
            urlReloadGrid: './index.php?r=estabelecimento/global-read&gridName=ExtratoMain',
            urlLoadGridPrefix: './index.php?r=estabelecimento/global-read&gridName=',
            titleGridExtratoMain: "Transferências",
         }; 

        C7.init = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>