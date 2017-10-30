<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "CB16PEDIDO",
            currentController: 'estabelecimento',
            actionReloadGrid: 'DeliveryMain',
            gridReload: 'DeliveryMain',
            urlReloadGrid: './index.php?r=estabelecimento/global-read&gridName=DeliveryMain',
            urlLoadGridPrefix: './index.php?r=estabelecimento/global-read&gridName=',
            titleWindowMain: "Status da entrega",
         }; 

        C7.init = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>