<script type="text/javascript">

    // functions
    var loadGrid = {};
    var modalEmpresa = {};
    var gridEmpresa = {};
    var gridEmpresa_empresaAtivo = {};
    var gridEmpresa_editar = {};

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        modalEmpresa = function (id) {
            if (typeof id == 'undefined') {
                titulo = "Nova empresa";
                urlGet = "";
            } else {
                titulo = "Editar empresa";
                urlGet = "&empresa=" + id;
            }

            $.blockUI();
            $('#remoteModalEmpresaLabel').text(titulo);
            $('#remoteModalEmpresa').modal('show')
                    .find('.modal-body')
                    .html('')
                    .load('index.php?r=administrador/empresa-form' + urlGet, function () {
                        $.unblockUI();
                    });
        };


        gridEmpresa_editar = function (id) {
            modalEmpresa(id);
        };


        C7.init_empresa();

        C7.callbackLoadGridEmpresasMain = function () {};

        C7.load('Grid', 'EmpresasMain', 'grid-empresas');
        C7.exportGridToCSV('EmpresasMain');
        C7.grid.EmpresasMain.enableCopyMMS(true, false);
        C7.grid.EmpresasMain.attachEvent("onCheck", function (rId, cInd, state) {
            Util.ajaxGet('index.php?r=administrador/empresa-ativar&empresa=' + this.cells(rId, 0).getValue() + '&status=' + (state ? 1 : 0), false);
        });


    });

</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Button trigger modal produto -->
        <a href="javascript:void(0)" onclick="modalEmpresa()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar empresa &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Empresas <span></span>
        </h1>
    </div>
</div>

<!-- MODAL EMPRESA -->
<div class="modal fade" id="remoteModalEmpresa" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 950px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalEmpresaLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END EMPRESA -->

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body" id="grid-empresas" style="height: 570px; width: 100%;">

                <!--
                <table class="table table-bordered" id="gridEmpresas">
                    <thead>
                        <tr>
                            <th>COD</th>
                            <th style="width: 100%;">EMPRESA</th>
                            <th>ATIVO</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>                        
                    <tbody>
                    </tbody>
                </table>
                -->

            </div>

        </div>

    </article>
</div>