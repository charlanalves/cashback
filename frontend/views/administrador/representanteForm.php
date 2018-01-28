<style>
    .smart-form .inline-group .checkbox, .smart-form .inline-group .radio { 
        position: relative;
        top: 38px;
        margin-left: 31px;
    }
    .smart-form .inline-group .checkbox, .smart-form .inline-group .radio:first-child {
        margin-left: 0px;
    }

</style>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="#" id="representante-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB04_ID" value="" />

                    <fieldset>
                        <h3>Sobre o representante</h3>
                        <div class="row">
                            <section class="col col-6"><?= $al['CB04_NOME'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input type="text" name="CB04_NOME" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB04_CNPJ'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="number" name="CB04_CNPJ" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB04_EMAIL'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input type="email" name="CB04_EMAIL" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB04_TEL_NUMERO'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="number" name="CB04_TEL_NUMERO" placeholder="">
                                </label>
                            </section>
                        </div>
                        <section><?= $al['CB04_OBSERVACAO'] ?>
                            <label class="textarea"> <i class="icon-prepend fa fa-info-circle"></i>
                                <textarea rows="4" name="CB04_OBSERVACAO" placeholder=""></textarea> 
                            </label>
                        </section>
                    </fieldset>

                    <fieldset>
                        <h3>Dados Bancários (para transfências)</h3>
                        <div class="row">
                            <section class="col col-6"><?= $al['CB03_NOME_BANCO'] ?>
                                <label class="select">
                                    <select name="CB03_NOME_BANCO">
                                        <option value="Banco do Brasil" selected="">Banco do Brasil</option>
                                        <option value="Santander" selected="">Santander</option>
                                        <option value="Caixa Econômica" selected="">Caixa Econômica</option>
                                        <option value="Bradesco" selected="">Bradesco</option>
                                        <option value="Itaú" selected="">Itaú</option>                                        
                                    </select> <i></i> 
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB03_TP_CONTA'] ?>
                                <label class="select">
                                    <select name="CB03_TP_CONTA">
                                        <option value="1" selected="">Corrente</option>
                                        <option value="0" selected="">Poupança</option>
                                    </select> <i></i> 
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB03_AGENCIA'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="text" name="CB03_AGENCIA" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB03_NUM_CONTA'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="text" name="CB03_NUM_CONTA" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB03_SAQUE_MIN'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="text" name="CB03_SAQUE_MIN" placeholder="" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$">
                                </label>
                            </section>
                            <section class="col col-6"><?= $al['CB03_SAQUE_MAX'] ?>
                                <label class="input"> <i class="icon-prepend fa fa-suitcase"></i>
                                    <input required type="text" name="CB03_SAQUE_MAX" placeholder="" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$">
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h3>Endereço</h3>
                        <div class="row padding-top-15">
                            <section class="col col-2"><?= $al['CB04_END_CEP'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_CEP" placeholder="" data-mask="99999-999" onkeyup="buscaCEP(this.value)">
                                </label>
                            </section>
                            <section class="col col-8"><?= $al['CB04_END_LOGRADOURO'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_LOGRADOURO" placeholder="">
                                </label>
                            </section>
                            <section class="col col-2"><?= $al['CB04_END_NUMERO'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_NUMERO" placeholder="" maxlength="5">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-5"><?= $al['CB04_END_BAIRRO'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_BAIRRO" placeholder="">
                                </label>
                            </section>
                            <section class="col col-5"><?= $al['CB04_END_CIDADE'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_CIDADE" placeholder="">
                                </label>
                            </section>
                            <section class="col col-2"><?= $al['CB04_END_UF'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_UF" placeholder="">
                                </label>
                            </section>
                        </div>

                        <div class="row">
                            <section class="col col-4"><?= $al['CB04_END_COMPLEMENTO'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_COMPLEMENTO" placeholder="">
                                </label>
                            </section>
                            <section class="col col-4"><?= $al['CB04_END_LATITUDE'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_LATITUDE" placeholder="">
                                </label>
                            </section>
                            <section class="col col-4"><?= $al['CB04_END_LONGITUDE'] ?>
                                <label class="input">
                                    <input type="text" name="CB04_END_LONGITUDE" placeholder="">
                                </label>
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

<script>

    var ultimoCEP = '',
            FormRepresentante = {},
            representante = JSON.parse('<?= json_encode($representante) ?>'),
            callbackSaveRepresentante = function (data) {
                if (data.status === true) {
                    message = 'Representante salvo.';
                    type = 'success';
                    ico = 'check-circle';
                    loadGrid();
                    $('#remoteModalRepresentante').modal('hide');
                } else {
                    console.log(data)
                    if (typeof data.retorno !== 'undefined') {
                        message = data.retorno;
                    } else {
                        message = data.message;
                    }
                    type = 'danger';
                    ico = 'frown-o';
                }
                Util.smallBox(message, '', type, ico);
            };

    function buscaCEP(v) {
        v = v.replace('X', '');
        if (v.length === 9 && ultimoCEP !== v) {
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
            FormRepresentante.setFormData({
                CB04_END_LOGRADOURO: '',
                CB04_END_BAIRRO: '',
                CB04_END_CIDADE: '',
                CB04_END_UF: '',
                CB04_END_COMPLEMENTO: ''
            });

        } else {
            FormRepresentante.setFormData({
                CB04_END_LOGRADOURO: data.logradouro,
                CB04_END_BAIRRO: data.bairro,
                CB04_END_CIDADE: data.localidade,
                CB04_END_UF: data.uf,
                CB04_END_COMPLEMENTO: data.complemento
            });
        }
    }

    // obj form
    FormRepresentante = new Form('representante-form');

    if (typeof representante.CB04_ID !== 'undefined') {

        // Preenche o form com os dados da representante
        FormRepresentante.setFormData(representante);

        $("#btn-reset").click(function (e) {
            FormRepresentante.setFormData(representante);
        });

    } else {
        $("#btn-reset").hide();
    }


    $("#btn-salvar").click(function (e) {
        FormRepresentante.form.submit();
    });

    pageSetUp();

    var pagefunction = function () {

        var $representanteForm = FormRepresentante.form.validate({
            rules: {
                CB04_NOME: {
                    required: true
                },
                CB04_EMAIL: {
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
                CB04_TEL_NUMERO: {
                    required: true
                },
                CB04_END_CIDADE: {
                    required: true
                },
                CB04_END_NUMERO: {
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
                CB04_EMAIL: {
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
                CB04_TEL_NUMERO: {
                    required: 'Campo obrigatório'
                },
                CB04_END_CIDADE: {
                    required: 'Campo obrigatório'
                },
                CB04_END_NUMERO: {
                    required: 'Campo obrigatório'
                },
                CB04_END_UF: {
                    required: 'Campo obrigatório'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function () {
                FormRepresentante.send('index.php?r=administrador/global-crud&action=saveRepresentante', callbackSaveRepresentante);
            }
        });
    };

    // Load form valisation dependency 
    loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);


</script>