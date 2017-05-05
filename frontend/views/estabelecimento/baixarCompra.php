<script type="text/javascript">

    var baixarCompra = loadGrid = callbackBaixarCompra = {};

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        // input cpf
        var inputCPF = $('#buscaCliente').find('input[name=cpf]');

        // cpf mask
        inputCPF.mask("999.999.999-99");

        // ao clicar
        $('#buscaCliente').find('button').on('click', function () {
            loadGrid();
        });

        // ao press "Enter"
        $('#buscaCliente').find('input').keypress(function (e) {
            if (e.which === 13) {
                loadGrid();
            }
        });

        loadGrid = function () {
            var cpf = inputCPF.val();
            if (cpf) {
                $.blockUI();
                $('#gridPedidos').find('tbody').load('index.php?r=estabelecimento/baixar-compra-grid&cpf=' + cpf, function () {
                    $.unblockUI();
                });
            }
        }

        baixarCompra = function (obj) {
            var pedido = obj.value;
            $.SmartMessageBox({
                title: "Deseja realizar a baixa do pedido " + pedido + "?",
                buttons: '[Não][Sim]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Sim") {
                    Util.ajaxPost('index.php?r=estabelecimento/global-crud&action=saveBaixaCompra', {pedido: pedido}, callbackBaixarCompra);
                }
            });
        }

        callbackBaixarCompra = function (data) {
            if (data.responseText) {
                Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
            } else {
                loadGrid();
            }
        }

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
            <i class="fa-fw fa fa-arrow-circle-down"></i> 
            Baixar Compra <span></span>
        </h1>
    </div>
</div>

<div class="row">

    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div class="form-group">

            <div class="col-md-12 no-padding margin-bottom-10">
                <div class="input-group input-group-lg" id="buscaCliente">
                    <div class="icon-addon addon-lg">
                        <input type="text" placeholder="CPF" class="form-control" style="background: transparent" name="cpf">
                        <label for="CPF" class="glyphicon glyphicon-search" rel="tooltip" title="" data-original-title="CPF"></label>
                    </div>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Buscar</button>
                    </span>
                </div>
                <small class="text-muted">informe o CPF do cliente para pesquisar</small>
            </div>

        </div>

        <div role="content">

            <div class="widget-body">


                <table class="table table-bordered" id="gridPedidos">
                    <thead>
                        <tr>
                            <th>PEDIDO</th>
                            <th>DATA</th>
                            <th style="width: 35%; text-align: left">CLIENTE</th>
                            <th style="width: 65%; text-align: left">PRODUTO</th>
                            <th>STATUS</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>                        
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div>

    </article>
</div>