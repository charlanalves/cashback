<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            FormCashback = {},
            produto = JSON.parse('<?= json_encode($produto) ?>'),
            promocao = JSON.parse('<?= json_encode($variacao) ?>'),
            callbackSaveCashback = function (data) {
                if (data.status == true) {
                    message = 'Cashback cadastrado.';
                    type = 'success';
                    ico = 'check-circle';
                    $('#remoteModalPromocao').modal('hide');
                    reloadPage();
                } else {
                    message = 'O cashback não foi cadastrado, tente novamente.';
                    type = 'danger';
                    ico = 'frown-o';
                }
                Util.smallBox(message, '', type, ico);
            };

    // obj form
    FormCashback = new Form('cashback-form');

    FormCashback.setMoney(['DIA_SEG', 'DIA_TER', 'DIA_QUA', 'DIA_QUI', 'DIA_SEX', 'DIA_SAB', 'DIA_DOM']);

    FormCashback.setFormData({DIA_SEG: '0,00', DIA_TER: '0,00', DIA_QUA: '0,00', DIA_QUI: '0,00', DIA_SEX: '0,00', DIA_SAB: '0,00', DIA_DOM: '0,00'});

    // add opcoes no select
    FormCashback.addOptionsSelect('PRODUTO_VARIACAO', [{ID: produto.CB05_ID, TEXTO: produto.CB05_NOME_CURTO + ' - ' + produto.CB05_TITULO}]);
    FormCashback.addOptionsSelect('PRODUTO_VARIACAO', promocao);

    $("#btn-salvar").click(function (e) {
        FormCashback.form.submit();
    });
    
    function loadGridCashback(produto){
        $('div#grid-cashback').load('index.php?r=estabelecimento/cashback-grid&produto=' + produto);
    }
    
    loadGridCashback(produto.CB05_ID);
    
    pageSetUp();

    var pagefunction = function () {

        var $cashbackForm = FormCashback.form.validate({
            rules: {
                PRODUTO_VARIACAO: {
                    required: true
                }
            },
            messages: {
                PRODUTO_VARIACAO: {
                    required: 'Campo obrigatório'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function () {
                FormCashback.send('index.php?r=estabelecimento/global-crud&action=saveCashback', callbackSaveCashback);
            }
        });
    };

    // Load form valisation dependency 
    loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

</script>

<style>
    .table-cashback-produto th {
        padding: 5px!important;
    }
    .table-cashback-produto input {
        padding-left: 3px!important;
        padding-right: 33px!important;
    }
</style>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="" id="cashback-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <fieldset>
                        <div class="row">
                            <section class="col col-4">
                                <h3>Produto/Promoção</h3>
                                <label class="select">
                                    <select name="PRODUTO_VARIACAO">
                                        <option value="" selected="" disabled="">Selecione...</option>
                                    </select> <i></i> 
                                </label>
                            </section>
                            <section class="col col-8">
                                <h3>CASHBACK</h3>
                                <table class="table table-bordered table-striped table-cashback-produto">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div>SEG<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_SEG" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>TER<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_TER" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>QUA<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_QUA" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>QUI<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_QUI" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>SEX<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_SEX" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>SAB<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_SAB" placeholder="" maxlength="5"></label></div>
                                            </th>
                                            <th>
                                                <div>DOM<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_DOM" placeholder="" maxlength="5"></label></div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </section>
                        </div>
                    </fieldset>
                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px">
                            Salvar
                        </button>
                        <button id="btn-cancelar" type="button" class="btn btn-danger" data-dismiss="modal" style="margin:0px 4px">
                            Cancelar
                        </button>
                    </footer>
                </form>

                <fieldset>
                    <div id="grid-cashback"></div>
                </fieldset>

            </div>

        </div>

    </article>
</div>