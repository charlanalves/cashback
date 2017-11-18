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
                console.log(data)
                if (data.status == true) {
                    message = 'Cashback cadastrado.';
                    messageBody = '';
                    type = 'success';
                    ico = 'check-circle';
                    time = 4000;    
                    $('#remoteModalCashback').modal('hide');
                } else {
                    message = 'O cashback não foi cadastrado.';
                    messageBody = data.message;
                    type = 'danger';
                    ico = 'frown-o';
                    time = 8000;
                }
                Util.smallBox(message, messageBody, type, ico, time);
            };

    // obj form
    FormCashback = new Form('cashback-form');

    FormCashback.setMoney(['DIA_1', 'DIA_2', 'DIA_3', 'DIA_4', 'DIA_5', 'DIA_6', 'DIA_0']);
    
    // Carrega os cashbacks ja cadastrados anteriormente para edição
    FormCashback.setFormData(<?=$cashback?>);
    
    // add opcoes no select
    FormCashback.addOptionsSelect('PRODUTO_VARIACAO', [{ID: 'P' + produto.CB05_ID, TEXTO: produto.CB05_NOME_CURTO + ' - ' + produto.CB05_TITULO}]);
    FormCashback.addOptionsSelect('PRODUTO_VARIACAO', promocao);

    $("#btn-salvar").click(function (e) {
        FormCashback.form.submit();
    });
    
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
                    <input type="hidden" name="CB07_EMPRESA_ID" value="<?= $empresa['CB04_ID'] ?>" />
                    <fieldset>
                        <div class="row">
<!--                            <section class="col col-3">
                                <h3>Produto/Promoção</h3>
                                <label class="select">
                                    <select name="PRODUTO_VARIACAO">
                                        <option value="" selected="" disabled="">Selecione...</option>
                                    </select> <i></i> 
                                </label>
                            </section>-->
                            <section class="col col-9" style="padding-left: 0px;">
                                <!--<h3>Cashback</h3>-->
                                <table class="table table-bordered table-striped table-cashback-produto">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div>SEG<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_1" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>TER<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_2" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>QUA<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_3" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>QUI<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_4" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>SEX<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_5" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>SAB<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_6" placeholder="" maxlength="6"></label></div>
                                            </th>
                                            <th>
                                                <div>DOM<br><label class="input"><i class="icon-append fa fa-percent"></i><input type="text" name="DIA_0" placeholder="" maxlength="6"></label></div>
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

            </div>

        </div>

    </article>
</div>