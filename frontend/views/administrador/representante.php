<script type="text/javascript">

    // functions
    var loadGrid = {};
    var modalRepresentante = {};
    var gridRepresentante = {};
    var gridRepresentante_representanteAtivo = {};
    var gridRepresentante_editar = {};

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        modalRepresentante = function (id) {
            if (typeof id === 'undefined') {
                titulo = "Novo representante";
                urlGet = "";
            } else {
                titulo = "Editar representante";
                urlGet = "&representante=" + id;
            }

            $.blockUI();
            $('#remoteModalRepresentanteLabel').text(titulo);
            $('#remoteModalRepresentante').modal('show')
                    .find('.modal-body')
                    .html('')
                    .load('index.php?r=administrador/representante-form' + urlGet, function () {
                        $.unblockUI();
                    });
        };


        gridRepresentante_editar = function (id) {
            modalRepresentante(id);
        };


        C7.init();

        C7.callbackLoadGridRepresentantesMain = function () {
            C7.grid.RepresentantesMain.attachEvent("onCheck", function (rId, cInd, state) {
                Util.ajaxGet('index.php?r=administrador/representante-ativar&representante=' + this.cells(rId, 0).getValue() + '&status=' + (state ? 1 : 0), false);
            });
        };

        C7.load('Grid', 'RepresentantesMain', 'grid-representantes');
        C7.exportGridToCSV('RepresentantesMain');
        C7.grid.RepresentantesMain.enableCopyMMS(true, false);


    });

</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Button trigger modal produto -->
        <a href="javascript:void(0)" onclick="modalRepresentante()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar representante &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Representantes <span></span>
        </h1>
    </div>
</div>

<!-- MODAL REPRESENTANTE -->
<div class="modal fade" id="remoteModalRepresentante" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 950px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalRepresentanteLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END REPRESENTANTE -->

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body" id="grid-representantes" style="height: 570px; width: 100%;">

                <!--
                <table class="table table-bordered" id="gridRepresentantes">
                    <thead>
                        <tr>
                            <th>COD</th>
                            <th style="width: 100%;">REPRESENTANTE</th>
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