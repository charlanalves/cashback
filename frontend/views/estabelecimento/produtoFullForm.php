<?php

if ($maxProduto) {
    echo '<script type="text/javascript"> $("#remoteModalProduto").modal("hide"); Util.smallBox("' . $maxProduto . '", "", "danger", "frown-o", 8000);</script>';
    exit();
}

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            FormProduto = {},
            produto = JSON.parse('<?= json_encode($produto) ?>'),
            itemProduto = JSON.parse('<?= json_encode($itemProduto) ?>'),
            limitFotos = JSON.parse('<?= json_encode($limitFotos) ?>'),
            avaliacoes = JSON.parse('<?= json_encode($avaliacoes) ?>'),
            callbackSaveProduto = function (data) {
                if (data.status == true) {
                    message = 'Dados salvos com sucesso.';
                    type = 'success';
                    ico = 'check-circle';
                    reloadPage();
                    $('#remoteModalProduto').modal('hide');
                } else {
                    message = data.message;
                    type = 'danger';
                    ico = 'frown-o';
                }
                Util.smallBox(message, '', type, ico);
            };

    
    var myDropzone;

    // cria dropzone para as fotos, nao faz o upload automaticamente, é necessário utilizar o "send" do objeto Form
    Util.dropZoneAsync('dropzoneProdutoFull', {
        maxFiles: limitFotos,
        message: "Enviar fotos",
    });
    $('#limitFotos').html("Permitido o envio de até <strong>" + limitFotos + "</strong> fotos.");
    $('#fieldset-fotos').show();
    
    // load html da promoção
    $('div#promocao').load('index.php?r=estabelecimento/promocao-form&produto=&promocao=', {}, function(){

        // obj form
        FormProduto = new Form('produto-form-full');

        // cria checkbox com as formas de pagamento
        FormProduto.addCheckboxInLine("item-produto", "ITEM-PRODUTO", itemProduto);

        // Opcoes com as Avaliações cadastradas
        FormProduto.addOptionsSelect('CB06_AVALIACAO_ID', avaliacoes);
        
        // Preenche o form com os dados da produto se for edicao
        if (produto.CB05_ID) {
            FormProduto.setFormData(produto);
        }

        // add formatação de moeda para o campo preço
        FormProduto.setMoney(['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA']);

        // ao alterar a opcao de distribuicao
        FormProduto.form.find("select[name=CB06_DISTRIBUICAO]").change(function (e) {
            // se for delivery preenche o campos de tempo min e max
            if(this.value == 1) {
                FormProduto.form.find("#CB06_TEMPO_MIN").show();
                FormProduto.form.find("#CB06_TEMPO_MAX").show();
            } else {
                FormProduto.form.find("#CB06_TEMPO_MIN").hide();
                FormProduto.form.find("#CB06_TEMPO_MAX").hide();
            } 
        });

        $("div#modalProdutoFullForm #btn-reset").click(function (e) {
            FormProduto.setFormData(produto);
        });

        $("div#modalProdutoFullForm #btn-salvar").click(function (e) {
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
                    },
                    CB06_DESCRICAO: {
                        required: true
                    },
                    CB06_PRECO: {
                        required: true
                    },
                    CB06_PRECO_PROMOCIONAL: {
                        required: true
                    },
                    CB06_DINHEIRO_VOLTA: {
                        required: true
                    },
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
                    },
                    CB06_DESCRICAO: {
                        required: 'Campo obrigatório'
                    },
                    CB06_PRECO: {
                        required: 'Campo obrigatório'
                    },
                    CB06_PRECO_PROMOCIONAL: {
                        required: 'Campo obrigatório'
                    },
                    CB06_DINHEIRO_VOLTA: {
                        required: 'Campo obrigatório'
                    },
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function () {
                    FormProduto.send('index.php?r=estabelecimento/global-crud&action=createProdutoFull', callbackSaveProduto);
                }
            });
        };

        // Load form valisation dependency 
        loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

        $('div#modalProdutoFullForm').show();

    });

</script>


<div class="row" id="modalProdutoFullForm" style="display: none;">

    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="" id="produto-form-full" class="smart-form" novalidate="novalidate" method="post" enctype='multipart/form-data'>
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB05_ID" value="" />
                    <fieldset>
                        <h3>Sobre o produto</h3>
                        <div class="row padding-top-15">
                            <section class="col col-6" style="display:none;">
                                <label class="input"> <i class="icon-prepend fa fa-tags"></i>
                                    <input type="text" name="CB05_NOME_CURTO" placeholder="<?= $al['CB05_NOME_CURTO'] ?>">
                                </label>
                            </section>
                            <section class="col col-6">                             
                                <label class="textarea"> Título<span id="titulo" class="tooltipestalecas tooltipSmallFont"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                <label class="input"> <i class="icon-prepend fa fa-product-hunt"></i>
                                    <input type="text" name="CB05_TITULO">
                                </label>
                            </section>
                        </div>
                        <div class="row">                           
                            <section class="col col-6">
                                <label class="textarea"> Descrição <span id="descricao_produto" class="tooltipestalecas tooltipSmallFont"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="5" name="CB05_DESCRICAO" ></textarea> 
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> Importante <span id="regras" class="tooltipestalecas tooltipSmallFont"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="5" name="CB05_IMPORTANTE" ></textarea> 
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h3>
                            Itens do produto
                            <span id="itens" class="tooltipestalecas tooltipSmallFont"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                        <section id="item-produto" class="padding-top-15"></section>
                        </h3>
                    </fieldset>

                    <fieldset id="fieldset-fotos">
                        <h3>Fotos<span id="imgs_produto" class="tooltipestalecas tooltipSmallFont"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                        <div class="row no-margin padding-top-15">
                            <div id="dropzoneProdutoFull"></div>
                        </div>
                        </h3>
                        <small id="limitFotos"></small>
                    </fieldset>

                    <fieldset id="fieldset-fotos">
                        <h3>Promoção</h3>
                        <div class="row no-margin padding-top-15">
                            <div id="promocao"></div>
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
<?php
    echo '<script type="text/javascript">   
         $(document).ready(function() {
          $(".tooltipestalecas").tooltipsterESTALECAS();  
        });
        </script>';
    