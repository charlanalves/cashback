<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();

        loadGrid = function () {
            $.blockUI();
            $('#gridExtrato').find('tbody').load('index.php?r=estabelecimento/extrato-grid', function () {
                $.unblockUI();
            });
        }
        
        loadGrid();

    });
</script>

<style>
    th {
        text-align: center;
    }
    .table-bordered tbody:hover {

    }
    .table-bordered tbody tr h3 {
        margin: 0px
    }
    .labelStatusCliente {
        /* display: inline; */
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        /* line-height: 1; */
        color: #fff;
        /* text-align: center; */
        /* white-space: nowrap; */
        vertical-align: sub;
        border-radius: .25em;
    }

</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-list-ul"></i> 
            Extrato <span></span>
            <div style="float: right; text-align: right;font-weight: bold;color: #36ac3b;">
                <span class="">Saldo Disponível</span>
                <div>R$ <?= $saldoAtual ?></div>
            </div>
            <div style="float: right; text-align: right;   margin-right: 77px;color: #0f4e9e;font-weight: bold;">
                <span class="">Saldo a Receber</span>
                <div>R$ <?= $saldoReceber ?></div>
            </div>
        </h1>
    </div>
</div>

<div class="row">

    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body">

                

                <table class="table table-bordered" id="gridExtrato">
                    <thead>
                        <tr>
                            <th>DATA DA OPERAÇÃO</th>
                            <th>LIBERAÇÃO DE PAGAMENTO</th>
                            <!--<th>DATA DO PAGAMENTO</th>-->
                            <th style="">REFERÊNCIA</th>
                            <th style="width: 30%; text-align: left">DESCRIÇÃO</th>
                            <th>VALOR</th>
                        </tr>
                    </thead>                        
                    <tbody>
                    </tbody>
<!--                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align: right">TOTAL</th>
                            <th style="">VALOR</th>
                        </tr>
                    </tfoot>-->
                </table>

            </div>

        </div>

    </article>
</div>