<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        C7.init();
        
        C7.callbackLoadGridExtratoMain = function () {};
        
        C7.load('Grid', 'ExtratoMain', 'gridExtrato');

        C7.exportGridToCSV('ExtratoMain');
        
        C7.grid.ExtratoMain.enableCopyMMS();
    });
    
</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-list-ul"></i> 
            Extrato <span></span>
            <div style="color: <?= ((int)$saldoAtual > 0 ) ? '#36ac3b' : ((int)$saldoAtual == 0 ? '#0f4e9e' : 'red')?>;font-weight: bold;float: right; text-align: right;">
                <span class="">Saldo Liberado</span>
                <div>R$ <?= $saldoAtual ?></div>
            </div>
            <div style="color: <?= ((int)$saldoReceber > 0 ) ? '#36ac3b' : ((int)$saldoReceber == 0 ? '#0f4e9e' : 'red')?>;font-weight: bold;float: right; text-align: right;   margin-right: 77px;">
                <span class="">Aguardando Liberação</span>
                <div>R$ <?= $saldoReceber ?></div>
            </div>
        </h1>
    </div>
</div>

<div class="row">

    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body dx-grid" id="gridExtrato"></div>

        </div>

    </article>
    
</div>