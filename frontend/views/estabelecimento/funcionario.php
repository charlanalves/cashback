<script type="text/javascript">

    // functions
    var empresa = <?= $empresa ?>;
    var loadGrid = {};
    var modalFuncionario = {};
    var gridFuncionario = {};
    var gridFuncionario_funcionarioAtivo = {};
    var gridFuncionario_editar = {};

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();

        urlGet = "&empresa=" + empresa;
        modalFuncionario = function (id) {
            if (typeof id == 'undefined') {
                titulo = "Novo funcionário";
                urlGetF = "";
            } else {
                titulo = "Editar funcionário";
                urlGetF = "&funcionario=" + id;
            }

            $.blockUI();
            $('#remoteModalFuncionarioLabel').text(titulo);
            $('#remoteModalFuncionario').modal('show')
                    .find('.modal-body')
                    .html('')
                    .load('index.php?r=estabelecimento/funcionario-form' + urlGet + urlGetF, function () {
                        $.unblockUI();
                    });
        };


        gridFuncionario_editar = function (id) {
            modalFuncionario(id);
        };


        C7.init();

        C7.callbackLoadGridFuncionariosMain = function () {};

        C7.load('Grid', 'FuncionariosMain', 'grid-funcionarios', {empresa});
        C7.exportGridToCSV('FuncionariosMain');
        C7.grid.FuncionariosMain.enableCopyMMS(true, false);
        C7.grid.FuncionariosMain.attachEvent("onCheck", function (rId, cInd, state) {
            Util.ajaxGet('index.php?r=estabelecimento/funcionario-ativar&funcionario=' + this.cells(rId, 0).getValue() + '&status=' + (state ? 1 : 0), false);
        });


    });

</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Button trigger modal produto -->
        <a href="javascript:void(0)" onclick="modalFuncionario()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar funcionário &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Funcionários <span></span>
        </h1>
    </div>
</div>

<!-- MODAL FUNCIONARIO -->
<div class="modal fade" id="remoteModalFuncionario" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 950px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalFuncionarioLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END FUNCIONARIO -->

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body" id="grid-funcionarios" style="height: 570px; width: 100%;"></div>

        </div>

    </article>
</div>