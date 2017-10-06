
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

    function loadGaleria() {
        var loadImgens = function (retorno) {
            fotos = JSON.parse(retorno.message);
            objFotos = [];  
            for (var i in fotos) {
                objFotos.push({
                    imgUrl: fotos[i].TEXTO,
                    imgDelete: 'excluirImg(' + fotos[i].ID + ')'
                });
            }
            Util.galeria('galeria', objFotos);
        }
        Util.ajaxGet('index.php?r=estabelecimento/global-crud', {action: 'fotoProduto', param: 'read', produto: produto.CB05_ID}, loadImgens);
    }
    
    function excluirImg(id) {
        Util.ajaxGet('index.php?r=estabelecimento/global-crud', {action: 'fotoProduto', param: 'delete', foto: id,  produto: produto.CB05_ID}, loadGaleria);
    }
    
    // obj form
    FormProduto = new Form('produto-form');

    // cria checkbox com as formas de pagamento
    FormProduto.addCheckboxInLine("item-produto", "ITEM-PRODUTO", itemProduto);

    // Preenche o form com os dados da produto se for edicao
    if (produto.CB05_ID) {
        FormProduto.setFormData(produto);

        Util.dropZone('dropzone', {
            urlSave: "index.php?r=estabelecimento/global-crud&action=fotoProduto&param=save&produto=" + produto.CB05_ID,
//            maxFiles: limitFotos,
            message: "Enviar fotos",
        }, loadGaleria);
        loadGaleria();
        $('#limitFotos').html("Permitido o envio de até <strong>" + limitFotos + "</strong> fotos.");
        $('#fieldset-fotos').show();
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
                            <section class="col col-6" style="display:none;">
                                <label class="input"> <i class="icon-prepend fa fa-tags"></i>
                                    <input type="text" name="CB05_NOME_CURTO" placeholder="<?= $al['CB05_NOME_CURTO'] ?>">
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> Título</label>
                                <label class="input"> <i class="icon-prepend fa fa-product-hunt"></i>
                                    <input type="text" name="CB05_TITULO">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                                <a href="https://lh3.googleusercontent.com/-xPPNMmx1EzQ/WdbZDCiS_hI/AAAAAAAAS98/Z79FQrGOH1g2Sx2DxRZ5pK7bzZ_SVExmQCL0BGAYYCw/h768/2017-10-05.png" class="imgpreview">
                                <label class="textarea showTip L3"> Descrição<span id="aws" style='font-size: 11px; margin-left: 8px;'>(Será exibido abaixo da imagem do produto no APP.)</span></label></a>
                                <label class="textarea"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <textarea rows="5" name="CB05_DESCRICAO"></textarea> 
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> Regras da Promoção <span style='font-size: 11px; margin-left: 8px;'>(Será exibido na aba regras no APP.)</span></label>
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="5" name="CB05_IMPORTANTE" ></textarea> 
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h3>Itens do produto</h3>
                        <section id="item-produto" class="padding-top-15"></section>
                    </fieldset>

                    <fieldset id="fieldset-fotos" style="display: none">
                        <h3>Fotos</h3>
                        <div class="row no-margin padding-top-15">
                            <div id="dropzone"></div>
                        </div>
                        <div id="galeria"></div>
                        <small id="limitFotos"></small>
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
        $(".imgpreview").anarchytip({
  xOffset:800, // default position
  yOffset:200 // default position
})</script>';
    exit();