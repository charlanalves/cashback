<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    var ultimoCEP = '',
            salvo = '<?= $salvo ?>',
            FormEmpresa = {},
            estabelecimento = JSON.parse('<?= json_encode($estabelecimento) ?>'),
            categorias = JSON.parse('<?= json_encode($categorias) ?>'),
            formaPagamento = JSON.parse('<?= json_encode($formaPagamento) ?>');

    function buscaCEP(v) {
        v = v.replace('X', '');
        if (v.length === 9 && ultimoCEP != v) {
            ultimoCEP = v;
            Util.getEnderecoByCEP(v, preencheEndereco);
        }
    }

    function preencheEndereco(data) {
        if (data.erro) {
            $.smallBox({
                title: "Busca de CEP",
                content: "<i class='fa fa-clock-o'></i> <i>O CEP digitado não foi encontrado, verifique o CEP ou preencha os dados do endereço...</i>",
                color: "#C46A69",
                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                timeout: 8000
            });
            FormEmpresa.setFormData({
                CB04_END_LOGRADOURO: '',
                CB04_END_BAIRRO: '',
                CB04_END_CIDADE: '',
                CB04_END_UF: '',
                CB04_END_COMPLEMENTO: ''
            });

        } else {
            FormEmpresa.setFormData({
                CB04_END_LOGRADOURO: data.logradouro,
                CB04_END_BAIRRO: data.bairro,
                CB04_END_CIDADE: data.localidade,
                CB04_END_UF: data.uf,
                CB04_END_COMPLEMENTO: data.complemento
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();

        // obj form
        FormEmpresa = new Form('empresa-form');

        // add opcoes no select
        FormEmpresa.addOptionsSelect('CB04_CATEGORIA_ID', categorias);

        // cria checkbox com as formas de pagamento
        FormEmpresa.addCheckboxInLine("forma-pagamento", "FORMA-PAGAMENTO", formaPagamento);

        // Preenche o form com os dados da empresa
        FormEmpresa.setFormData(estabelecimento);

        $("#btn-reset").click(function (e) {
            FormEmpresa.setFormData(estabelecimento);
        });

        $("#btn-salvar").click(function (e) {
            FormEmpresa.form.submit();
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

            var $empresaForm = FormEmpresa.form.validate({
                rules: {
                    CB04_NOME: {
                        required: true
                    },
                    CB04_CATEGORIA_ID: {
                        required: true
                    },
                    CB04_END_CEP: {
                        required: true
                    },
                    CB04_END_LOGRADOURO: {
                        required: true
                    },
                    CB04_END_BAIRRO: {
                        required: true
                    },
                    CB04_END_CIDADE: {
                        required: true
                    },
                    CB04_END_UF: {
                        required: true
                    }
                },
                messages: {
                    CB04_NOME: {
                        required: 'Campo obrigatório'
                    },
                    CB04_CATEGORIA_ID: {
                        required: 'Campo obrigatório'
                    },
                    CB04_END_CEP: {
                        required: 'Campo obrigatório'
                    },
                    CB04_END_LOGRADOURO: {
                        required: 'Campo obrigatório'
                    },
                    CB04_END_BAIRRO: {
                        required: 'Campo obrigatório'
                    },
                    CB04_END_CIDADE: {
                        required: 'Campo obrigatório'
                    },
                    CB04_END_UF: {
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
        
    });
</script>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-pencil-square-o"></i> 
            Empresa <span>&gt; edição</span>
        </h1>
    </div>
</div>


<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="" id="empresa-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <fieldset>
                        <h3>Sobre a empresa</h3>
                        <div class="row padding-top-15">
                            <section class="col col-6">
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input type="text" name="CB04_NOME" placeholder="<?= $al['CB04_NOME'] ?>">
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="select">
                                    <select name="CB04_CATEGORIA_ID">
                                        <option value="" selected="" disabled="">Categoria...</option>
                                    </select> <i></i> 
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <textarea rows="4" name="CB04_FUNCIONAMENTO" placeholder="<?= $al['CB04_FUNCIONAMENTO'] ?>"></textarea> 
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                    <textarea rows="4" name="CB04_OBSERVACAO" placeholder="<?= $al['CB04_OBSERVACAO'] ?>"></textarea> 
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h3>Formas de pagamento</h3>
                        <section id="forma-pagamento" class="padding-top-15"></section>
                    </fieldset>

                    <fieldset>
                        <h3>Endereço</h3>
                        <div class="row padding-top-15">
                            <section class="col col-2 form-padding-right">
                                <label class="input">
                                    <input type="text" name="CB04_END_CEP" placeholder="<?= $al['CB04_END_CEP'] ?>" data-mask="99999-999" onkeyup="buscaCEP(this.value)">
                                </label>
                            </section>
                            <section class="col col-8 form-padding-left">
                                <label class="input">
                                    <input type="text" name="CB04_END_LOGRADOURO" placeholder="<?= $al['CB04_END_LOGRADOURO'] ?>">
                                </label>
                            </section>
                            <section class="col col-2 form-padding-right">
                                <label class="input">
                                    <input type="text" name="CB04_END_NUMERO" placeholder="<?= $al['CB04_END_NUMERO'] ?>">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-5 form-padding-right">
                                <label class="input">
                                    <input type="text" name="CB04_END_BAIRRO" placeholder="<?= $al['CB04_END_BAIRRO'] ?>">
                                </label>
                            </section>
                            <section class="col col-5 form-padding-left form-padding-right">
                                <label class="input">
                                    <input type="text" name="CB04_END_CIDADE" placeholder="<?= $al['CB04_END_CIDADE'] ?>">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="CB04_END_UF" placeholder="<?= $al['CB04_END_UF'] ?>">
                                </label>
                            </section>
                        </div>
                        <section>
                            <label class="input">
                                <input type="text" name="CB04_END_COMPLEMENTO" placeholder="<?= $al['CB04_END_COMPLEMENTO'] ?>">
                            </label>
                        </section>
                    </fieldset>

                    <fieldset>
                        <h3>Fotos</h3>
                        <div class="row no-margin padding-top-15">

                            <section>
                                <div class="widget-body dropzone dz-clickable" style="min-height: 200px">
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