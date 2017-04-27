<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            FormProduto = {},
            produto = JSON.parse('<?= json_encode($produto) ?>'),
            itemProduto = JSON.parse('<?= json_encode($itemProduto) ?>'),
            callbackSaveProduto = function (data) {
                if (data.status == true) {
                    message = 'Dados salvos com sucesso.';
                    type = 'success';
                    ico = 'check-circle';
                    $('#remoteModalProduto').modal('hide');
                    reloadPage();
                } else {
                    message = 'Os dados não foram atualizados, tente novamente.';
                    type = 'danger';
                    ico = 'frown-o';
                }
                Util.smallBox(message, '', type, ico);
            };

    // obj form
    FormProduto = new Form('produto-form');

    // cria checkbox com as formas de pagamento
    FormProduto.addCheckboxInLine("item-produto", "ITEM-PRODUTO", itemProduto);

    // Preenche o form com os dados da produto se for edicao
    if (produto) {
        FormProduto.setFormData(produto);
    }

    $("#btn-reset").click(function (e) {
        FormProduto.setFormData(produto);
    });

    $("#btn-salvar").click(function (e) {
        FormProduto.form.submit();
    });

    pageSetUp();

    var pagefunction = function () {

        var $produtoForm = FormProduto.form.validate({
            rules: {
                CB05_TITULO: {
                    required: true
                },
                CB05_NOME_CURTO: {
                    required: true
                },
                CB05_DESCRICAO: {
                    required: true
                },
                CB05_IMPORTANTE: {
                    required: true
                }
            },
            messages: {
                CB05_TITULO: {
                    required: 'Campo obrigatório'
                },
                CB05_NOME_CURTO: {
                    required: 'Campo obrigatório'
                },
                CB05_DESCRICAO: {
                    required: 'Campo obrigatório'
                },
                CB05_IMPORTANTE: {
                    required: 'Campo obrigatório'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function () {
                FormProduto.send('index.php?r=estabelecimento/global-crud&action=saveProduto', callbackSaveProduto);
            }
        });
    };

    // Load form valisation dependency 
    loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

</script>


<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="" id="produto-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB05_ID" value="" />
                    <fieldset>
                        <h3>Sobre o produto</h3>
                        <div class="row padding-top-15">
                            <section class="col col-6">
                                <label class="input"> <i class="icon-prepend fa fa-tags"></i>
                                    <input type="text" name="CB05_NOME_CURTO" placeholder="<?= $al['CB05_NOME_CURTO'] ?>">
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="input"> <i class="icon-prepend fa fa-product-hunt"></i>
                                    <input type="text" name="CB05_TITULO" placeholder="<?= $al['CB05_TITULO'] ?>">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <textarea rows="5" name="CB05_DESCRICAO" placeholder="<?= $al['CB05_DESCRICAO'] ?>"></textarea> 
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="5" name="CB05_IMPORTANTE" placeholder="<?= $al['CB05_IMPORTANTE'] ?>"></textarea> 
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h3>Itens do produto</h3>
                        <section id="item-produto" class="padding-top-15"></section>
                    </fieldset>

                    <fieldset>
                        <h3>Fotos</h3>
                        <div class="row no-margin padding-top-15">

                            <section>
                                <div class="widget-body dropzone dz-clickable" style="min-height: 140px">
                                    <div class="dz-default dz-message">
                                        <span>
                                            <span class="text-center">
                                                <span class="font-lg">
                                                    <span class="font-lg"><i class="fa fa-cloud-upload text-danger"></i> Enviar fotos </span><span>&nbsp;&nbsp;<h4 class="display-inline"> (clique aqui)</h4></span>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </fieldset>

                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px">
                            Salvar
                        </button>
                        <button id="btn-reset" type="button" class="btn btn-primary" style="margin:0px 4px">
                            Restaurar informações
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