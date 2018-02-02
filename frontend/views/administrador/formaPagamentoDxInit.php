<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        
        var conf = {
            currentCenterMethod: "global-crud",
            currentModule: "CB09FORMAPAGTOEMPRESA",
            currentController: 'administrador',
            actionReloadGrid: 'FormaPagamentoMain',
            gridReload: 'FormaPagamentoMain',
            urlReloadGrid: './index.php?r=administrador/global-read&gridName=FormaPagamentoMain',
            urlLoadGridPrefix: './index.php?r=administrador/global-read&gridName=',
            titleGridFormaPagamentoMain: 'Formas de pagamento e comissões',
         }; 

        C7.init_formaPagamento = function() {   
           $.extend(this.settings, conf);
        };

    });
    
</script>