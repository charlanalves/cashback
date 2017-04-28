<?php
/* @var $this yii\web\View */

if ($maxPromocao) {
    echo '<script type="text/javascript"> $("#remoteModalPromocao").modal("hide"); Util.smallBox("' . $maxPromocao . '", "", "danger", "frown-o", 8000);</script>';
    exit();
}

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            FormPromocao = {},
            produto = JSON.parse('<?= json_encode($produto) ?>'),
            callbackSavePromocao = function (data) {
                if (data.status == true) {
                    message = 'Promoção cadastrada.';
                    type = 'success';
                    ico = 'check-circle';
                    $('#remoteModalPromocao').modal('hide');
                    reloadPage();
                } else {
                    message = 'A promoção não foi cadastrada, tente novamente.';
                    type = 'danger';
                    ico = 'frown-o';
                }
                Util.smallBox(message, '', type, ico);
            };

    // obj form
    FormPromocao = new Form('promocao-form');

    // add formatação de moeda para o campo preço
    FormPromocao.setMoney(['CB06_PRECO']);

    // Preenche o form com os dados da produto se for edicao
    FormPromocao.setFormData(produto);
    

    $("#btn-salvar").click(function (e) {
        FormPromocao.form.submit();
    });

    pageSetUp();

    var pagefunction = function () {
        
        var $promocaoForm = FormPromocao.form.validate({
            rules: {
                CB06_DESCRICAO: {
                    required: true
                },
                CB06_PRECO: {
                    required: true
                }
            },
            messages: {
                CB06_DESCRICAO: {
                    required: 'Campo obrigatório'
                },
                CB06_PRECO: {
                    required: 'Campo obrigatório'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function () {
                FormPromocao.send('index.php?r=estabelecimento/global-crud&action=savePromocao', callbackSavePromocao);
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

                <form action="" id="promocao-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB06_PRODUTO_ID" value="" />
                    <fieldset>
                        <div class="row padding-top-15">
                            <section class="col col-8">
                                <label class="input"> <i class="icon-prepend fa fa-tags"></i>
                                    <input type="text" name="CB06_DESCRICAO" placeholder="<?= $al['CB06_DESCRICAO'] ?>">
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input"> <i class="icon-prepend fa fa-usd"></i>
                                    <input type="text" name="CB06_PRECO" placeholder="<?= $al['CB06_PRECO'] ?>" maxlength="8">
                                </label>
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