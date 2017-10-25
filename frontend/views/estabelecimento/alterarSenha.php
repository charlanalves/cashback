<?php
/* @var $this yii\web\View */

$this->title = '';
?>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        // obj form
        FormAlterarSenha = new Form('alterar-senha-form');

        $("#btn-salvar").click(function (e) {
            FormAlterarSenha.form.submit();
        });

        pageSetUp();

        var pagefunction = function () {

            var $alterarSenhaForm = FormAlterarSenha.form.validate({
                rules: {
                    'current-password': {
                        required: true
                    },
                    'new-password': {
                        required: true
                    },
                },
                messages: {
                    'current-password': {
                        required: 'Campo obrigatório'
                    },
                    'new-password': {
                        required: 'Campo obrigatório'
                    },
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function () {
                    FormAlterarSenha.send('index.php?r=estabelecimento/global-crud&action=alterarSenha', function(a){
                        if(a.status){
                            Util.smallBox(a.message, '', 'success');
                        }else {
                            Util.smallBox('Erro', a.message, 'danger');
                        }
                        FormAlterarSenha.clear();
                    });
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
            Senha <span>&gt; edição</span>
        </h1>
    </div>
</div>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="#" id="alterar-senha-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <fieldset>
                        <div class="row">
                            <section class="col col-6">Senha Atual
                                <label class="input"> <i class="icon-prepend fa fa-key"></i>
                                    <input type="password" name="current-password" placeholder="">
                                </label>
                            </section>
                            <section class="col col-6">Nova Senha
                                <label class="input"> <i class="icon-prepend fa fa-key"></i>
                                    <input type="password" name="new-password" placeholder="">
                                </label>
                            </section>
                        </div>
                    </fieldset>

                    <footer>
                        <button id="btn-salvar" type="button" class="btn btn-primary">
                            Salvar alteração
                        </button>
                    </footer>

                </form>

            </div>

        </div>

    </article>
</div>