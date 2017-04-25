<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            salvo = '<?= $salvo ?>',
            FormProduto = {},
            produto = JSON.parse('<?= json_encode($produto) ?>'),
            itemProduto = JSON.parse('<?= json_encode($itemProduto) ?>');

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

        if (salvo) {
            $.smallBox({
                title: "Dados atualizados",
                //content: "<i class='fa fa-clock-o'></i> <i></i>",
                color: "#739e73",
                iconSmall: "fa fa-check-circle fadeInRight animated",
                timeout: 4000
            });
        }

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
            }
        });
    };

    // Load form valisation dependency 
    loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Produto <span></span>
        </h1>
    </div>
</div>


<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">


                <form action="" id="produto-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <fieldset>
                        <h3>Sobre o produto</h3>
                        <div class="row padding-top-15">
                            <section class="col col-6">
                                <label class="input"> <i class="icon-prepend fa fa-product-hunt"></i>
                                    <input type="text" name="CB05_TITULO" placeholder="<?= $al['CB05_TITULO'] ?>">
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input type="text" name="CB05_NOME_CURTO" placeholder="<?= $al['CB05_NOME_CURTO'] ?>">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <textarea rows="4" name="CB05_DESCRICAO" placeholder="<?= $al['CB05_DESCRICAO'] ?>"></textarea> 
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="4" name="CB05_IMPORTANTE" placeholder="<?= $al['CB05_IMPORTANTE'] ?>"></textarea> 
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
                                <div class="widget-body dropzone dz-clickable">
                                    <div class="dz-default dz-message">
                                        <span>
                                            <span class="text-center">
                                                <span class="font-lg visible-xs-block visible-sm-block visible-lg-block">
                                                    <span class="font-lg"><i class="fa fa-cloud-upload text-danger"></i> Enviar fotos </span><span>&nbsp;&nbsp;<h4 class="display-inline"> (clique aqui)</h4></span>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </fieldset>

                    <footer>
                        <button id="btn-salvar" type="button" class="btn btn-primary">
                            Salvar
                        </button>
                        <button id="btn-reset" type="button" class="btn btn-default">
                            Restaurar informações
                        </button>
                    </footer>
                </form>

            </div>

        </div>

    </article>
</div>