<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            salvo = '<?= $salvo ?>',
            reloadPage = function () {window.location.reload(false);};

    function modalProduto(id) {
        if (typeof id == 'undefined') {
            titulo = "Novo produto";
            urlGet = "";
        } else {
            titulo = "Editar produto";
            urlGet = "&produto=" + id;
        }

        $('#remoteModalProdutoLabel').text(titulo);
        $('#remoteModalProduto').modal('show')
                .find('.modal-body')
                .html('')
                .load('index.php?r=estabelecimento/produto-form' + urlGet);
    }

    function modalPromocao(id) {
        $('#remoteModalPromocaoLabel').text('Nova promoção');
        $('#remoteModalPromocao').modal('show')
                .find('.modal-body')
                .html('')
                .load('index.php?r=estabelecimento/promocao-form&produto=' + id);
    }

    function modalCashback(id) {
        $('#remoteModalCashbackLabel').text('CASHBACK');
        $('#remoteModalCashback').modal('show')
                .find('.modal-body')
                .html('')
                .load('index.php?r=estabelecimento/cashback-form&produto=' + id);
    }
    
    function acaoProduto(obj, id) {
        funcao = window[obj.val()];
        funcao(id);
        obj.val('');
    }

    function excluirProduto(id) {
        $.SmartMessageBox({
            title: "Deseja excluir o produto?",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                var ajax = $.ajax({
                    url: 'index.php?r=estabelecimento/global-crud&action=deleteProduto',
                    type: 'POST',
                    data: {produto: id},
                    dataType: "json"
                });
                ajax.always(function (data) {
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        reloadPage();
                    }
                });
            }
        });
    }

    function excluirVariacao(id) {
        $.SmartMessageBox({
            title: "Deseja excluir a promoção?",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                var ajax = $.ajax({
                    url: 'index.php?r=estabelecimento/global-crud&action=deletePromocao',
                    type: 'POST',
                    data: {promocao: id},
                    dataType: "json"
                });
                ajax.always(function (data) {
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        reloadPage();
                    }
                });
            }
        });
    }

    function produtoAtivo(id) {
        var checkbox = $('#ativo-' + id)[0], status = checkbox.checked;
        var ajax = $.ajax({
            url: 'index.php?r=estabelecimento/produto-ativar&produto=' + id + '&status=' + (status ? 1 : 0),
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
        <a href="javascript:void(0)" onclick="modalProduto()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar produto &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Produto <span></span>
        </h1>
    </div>
</div>

<!-- MODAL CASHBACK -->
<div class="modal fade" id="remoteModalCashback" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 950px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalCashbackLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- MODAL PROMOCAO -->
<div class="modal fade" id="remoteModalPromocao" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalPromocaoLabel"></h4>
            </div>
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- MODAL PRODUTO -->
<div class="modal fade" id="remoteModalProduto" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="remoteModalProdutoLabel"></h4>
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

                <div class="table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 100%; text-align: left">PRODUTOS</th>
                                <th>ATIVO</th>
                                <th>AÇÕES</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($produto as $value) {
                            $at = $value['PRODUTO'];
                            ?>
                            <tbody style="background-color: #EFF5FB">
                                <tr id="tr-produto-<?= $at['CB05_ID'] ?>">
                                    <td><?= "<h3>" . $at['CB05_NOME_CURTO'] . " <small>" . $at['CB05_TITULO'] . "</small></h3>" ?></td>
                                    <td class="smart-form" style="padding: 5px 16px;">
                                        <label class="checkbox">
                                            <input type="checkbox" id="ativo-<?= $at['CB05_ID'] ?>" onchange="produtoAtivo(<?= $at['CB05_ID'] ?>)" value="<?= $at['CB05_ID'] ?>" <?= ($at['CB05_ATIVO']) ? "checked" : "" ?> /><i></i>
                                        </label>
                                    </td>
                                    <td>
                                        <select class="btn btn-primary btn-xs" onchange="acaoProduto($(this), <?= $at['CB05_ID'] ?>)">
                                            <option value="">Selecione</option>
                                            <option value="modalCashback">Cashback</option>
                                            <option value="modalPromocao">Promoções</option>
                                            <option value="modalProduto">Editar</option>
                                            <option value="excluirProduto">Excluir</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                if (!empty($at['VARIACAO'])) {
                                    ?>
                                    <tr id="tr-promocao-<?= $at['CB05_ID'] ?>">
                                        <td colspan="3" style="padding:0px 0px 0px 30px;border-top: 0px;">
                                            <table class="table" style="border-right: 0px; background-color: #FAFAFA; margin-bottom: 10px">
                                                <?php
                                                foreach ($at['VARIACAO'] as $variacao) {
                                                    ?>
                                                    <tr>
                                                        <td style="width: 100%;">
                                                            <strong>&bull; <?= $variacao['CB06_DESCRICAO'] ?></strong> <?= ($variacao['CB06_AVALIACAO_ID'] ? '(Avaliando)' : '') ?> <br /> 
                                                            Valor original: <strong>R$ <?= \Yii::$app->u->moedaReal($variacao['CB06_PRECO']) ?></strong> | Valor promocional: <strong>R$ <?= \Yii::$app->u->moedaReal($variacao['CB06_PRECO_PROMOCIONAL']) ?></strong> | Dinheiro de volta: <strong><?= \Yii::$app->u->moedaReal($variacao['CB06_DINHEIRO_VOLTA']) ?>%</strong>
                                                        </td>
                                                        <td align="center"><button class="btn btn-danger btn-xs margin-top-5" onclick="excluirVariacao(<?= $variacao['CB06_ID'] ?>)">Excluir &nbsp;<i class="fa fa-trash-o"></i></button></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <?php
                        }
                        ?>
                    </table>

                </div>

            </div>

        </div>

    </article>
</div>