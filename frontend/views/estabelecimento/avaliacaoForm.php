<?php
/* @var $this yii\web\View */

$this->title = '';
?>


<script type="text/javascript">

    var ultimoCEP = '',
            itens = JSON.parse('<?= json_encode($itens) ?>'),
            dataAvaliacao = JSON.parse('<?= json_encode($dataAvaliacao) ?>'),
            itensSelecionados = JSON.parse('<?= json_encode($itensSelecionados) ?>'),
            callbackSaveAvaliacao = function (data) {
                if (data.status == true) {
                    message = 'A avaliação foi salva.';
                    messageBody = '';
                    type = 'success';
                    ico = 'check-circle';
                    time = 4000;
                    $('#remoteModalAvaliacao').modal('hide');
                    // reload page - se cadastrar nova avaliacao
                    if(typeof dataAvaliacao.CB19_ID == 'undefined') {
                        //window.location.reload(false);
                    }
                } else {
                    message = 'A avaliação não foi salva.';
                    messageBody = data.message;
                    type = 'danger';
                    ico = 'frown-o';
                    time = 8000;
                }
                Util.smallBox(message, messageBody, type, ico, time);
            };

    // verifica se existem itens cadastdos para a categoria do estabelecimento
    if(!itens){
        Util.smallBox('Opss', 'Não existem itens de avaliação cadastrados, entre em contato com o suporte técnico.', 'danger', 'frown-o', 8000);
        $('#remoteModalAvaliacao').modal('hide');
    }

    // obj form
    Formavaliacao = new Form('avaliacao-form');

    // cria checkbox com as formas de pagamento
    Formavaliacao.addCheckboxInLine("avaliacao-itens", "AVALIACAO-ITENS", itens);

    // Preenche o form com os atuais
    if (itensSelecionados) {
        $.extend(dataAvaliacao, {'AVALIACAO-ITENS': itensSelecionados});
    }
    Formavaliacao.setFormData(dataAvaliacao);

    // evento de click no botao salvar
    $("#btn-salvar").click(function (e) {
        Formavaliacao.form.submit();
    });
    
    pageSetUp();

    var pagefunction = function () {

        var $avaliacaoForm = Formavaliacao.form.validate({
            rules: {
                CB19_NOME: {
                    required: true
                }
            },
            messages: {
                CB19_NOME: {
                    required: 'Campo obrigatório'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function () {
                Formavaliacao.send('index.php?r=estabelecimento/global-crud&action=saveAvaliacao', callbackSaveAvaliacao);
            }
        });
    };

    // Load form valisation dependency 
    loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

</script>

<style>
    .table-avaliacao-produto th {
        padding: 5px!important;
    }
    .table-avaliacao-produto input {
        padding-left: 3px!important;
        padding-right: 33px!important;
    }
</style>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="" id="avaliacao-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB19_ID" value="" />
                    <fieldset>
                        <div class="row">

                            <section class="col col-10"><?= $alAvaliacao['CB19_NOME'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-list-alt"></i>
                                    <input type="text" name="CB19_NOME" placeholder="">
                                </label>
                            </section>

                        </div>
                        <div class="row">

                            <section class="">
                                <fieldset>
                                    <h3>Itens</h3>
                                    <section id="avaliacao-itens" class="padding-top-15"></section>
                                </fieldset>
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