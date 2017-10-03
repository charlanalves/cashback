<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var reloadPage = function () {window.location.reload(false);};

    function modalAvaliacao(id) {
        if (typeof id == 'undefined') {
            titulo = "Nova avaliação";
            urlGet = "";
        } else {
            titulo = "Editar avaliação";
            urlGet = "&avaliacao=" + id;
        }

        $('#remoteModalAvaliacaoLabel').text(titulo);
        $('#remoteModalAvaliacao').modal('show')
                .find('.modal-body')
                .html('')
                .load('index.php?r=estabelecimento/avaliacao-form' + urlGet);
    }

    function avaliacaoAtiva(id) {
        var checkbox = $('#ativo-' + id)[0], status = checkbox.checked;
        var ajax = $.ajax({
            url: 'index.php?r=estabelecimento/avaliacao-ativar&avaliacao=' + id + '&status=' + (status ? 1 : 0),
            type: 'GET',
            dataType: "json"
        });
        ajax.always(function (data) {
            if (data.responseText) {
                checkbox.checked = !status;
                Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        pageSetUp();

        var pagefunction = function () {

        };

        // Load form valisation dependency 
        loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

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
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Button trigger modal produto -->
        <a href="javascript:void(0)" onclick="modalAvaliacao()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar Avaliação &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-list-alt"></i> 
            Avaliação <span></span>
        </h1>
    </div>
</div>

<!-- MODAL AVALIACAO -->
<div class="modal fade" id="remoteModalAvaliacao" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalAvaliacaoLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<div class="row bg-color-white">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <div lass="tab-content">
                    <div class="tab-pane fade active in padding-10 no-padding-bottom">
                        <h2>Avaliações cadastradas</h2>
                        <div class="table-responsive">

                            <?php
                            if(!$avaliacoes) {
                                echo 'Cadastre uma avaliação para saber o que seus clientes acharam dos produtos e/ou serviços oferecidos...';
                            } else {
                            ?>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 100%; text-align: left">NOME DA AVALIAÇÃO</th>
                                            <th>ATIVO</th>
                                            <th>EDITAR</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    foreach ($avaliacoes as $value) {
                                        $CB19_ID = $value['CB19_ID'];
                                        $CB19_STATUS = $value['CB19_STATUS'];
                                        $CB19_NOME = $value['CB19_NOME'];
                                    ?>
                                    <tbody style="">
                                        <tr id="tr-produto-<?= $CB19_ID ?>">
                                            <td>
                                                <h5 class="no-margin"><?= $CB19_NOME ?></h5>
                                            </td>
                                            <td class="smart-form" style="padding: 6px 17px;">
                                                <label class="checkbox">
                                                    <input type="checkbox" id="ativo-<?= $CB19_ID ?>" onchange="avaliacaoAtiva(<?= $CB19_ID ?>)" value="<?= $CB19_ID ?>" <?= ($CB19_STATUS) ? "checked" : "" ?> /><i></i>
                                                </label>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-xs" onclick="modalAvaliacao(<?= $CB19_ID ?>)">
                                                    EDITAR
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php } ?>
                                </table>

                            <?php } ?>

                        </div>
                    </div>
                </div>
                
                <div lass="tab-content">
                    <div class="tab-pane fade active in padding-10 no-padding-bottom">
                        <h2>Itens avaliados</h2>
                        <div class="row no-space">

                            <?php
                            if(!$itensAvaliados) {
                                echo 'Nenhum item foi avaliado ainda...';
                            } else {
                            foreach ($itensAvaliados as $value) {
                                $item = $value['CB23_DESCRICAO'];
                                $qtd = $value['QTD'];
                                $percentual = $value['PERCENTUAL'];
                                $color = ($percentual >= 70 ? 'green' : ($percentual <= 30 ? 'red' : 'orange'));
                            ?>

                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="easy-pie-chart txt-color-<?= $color ?>" data-percent="<?= $percentual ?>" data-pie-size="50" style="width: 50px">
                                    <span class="percent percent-sign">0</span>
                                </div>
                                <span class="easy-pie-title" style="height: auto; text-transform: none;">
                                    <?= $item ?>
                                    <br />
                                    <span class="label bg-color-blueDark"></i> <?= $qtd . ' ' . ($qtd > 1 ? 'avaliações' : 'avaliação') ?> </span>
                                </span>
                            </div>
                            
                            <?php } } ?>

                        </div>
                    </div>

                </div>


                <div lass="tab-content" style="margin-bottom: 30px;">
                    <div class="tab-pane fade active in padding-10 no-padding-bottom">
                        <h2>Comentários</h2>
                        <div class="row no-space" style="height: 350px; overflow: auto;">

                            <?php
                            if(!$comentarios) {
                                echo 'Você não possui comentários...';
                            } else {
                                foreach ($comentarios as $value) {
                                    $produto = $value['CB17_NOME_PRODUTO'];
                                    $data = $value['CB16_DT'];
                                    $comentario = $value['CB22_COMENTARIO'];
                                ?>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-10" style="border-bottom: 1px silver solid;">
                                    <span style="float: right;" class="label bg-color-blueDark"><?= $data ?></span>
                                    <span>
                                        <strong><?= $produto ?>:</strong> <?= $comentario ?></span>
                                </div>

                                <?php } } ?>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </article>
</div>