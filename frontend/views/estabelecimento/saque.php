<?php
/* @var $this yii\web\View */

$this->title = '';
if(!empty($sem_conta)) {
    echo "<h2><i class='icon-prepend fa fa-warning'></i> $sem_conta<h2>";
    
} else {

?>

<script type="text/javascript">

    var printError = function (error) {
        if ( typeof error != "undefined" ) {
            var errorStr = '';
            for (var i in error) {
                errorStr += "* " + error[i][0] + "<br />";
            }
            message = 'O saque não foi registrado';
            text = (errorStr || 'Tente novamente.');
            type = 'danger';
            ico = 'frown-o';
            time = 10000;
            Util.smallBox(message, text, type, ico, time);
            return true;
        } else {
            return false
        }
    };

    var ultimoCEP = '',
    salvo = '<?= $saque_realizado ?>',
    error = '<?= (empty($error) ? '' : json_encode($error)) ?>',
    FormSaque = {},
    reloadPage = function () {window.location.reload(false);};
    dataSaque = JSON.parse('<?= json_encode($dados_saque) ?>'),
    callbackSaque = function (data) {
        if (printError(data.error) === false) {
            message = 'Saque registrado';
            text = '';
            type = 'success';
            ico = 'check-circle';
            time = 5000;
            Util.smallBox(message, text, type, ico, time);
            reloadPage();
        }
    };

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();


        if(error) {
            $.smallBox({
                title: "Opss",
                content: error,
                color: "#739e73",
                iconSmall: "fa fa-check-circle fadeInRight animated",
                timeout: 4000
            });
        }

        // obj form
        FormSaque = new Form('saque-form');
        
        // campos monetários
        FormSaque.setMoney(['CB03_SAQUE_MAX','CB03_VALOR']);
        
        // Preenche o form
        FormSaque.setFormData(dataSaque);

        $("#btn-sacar").click(function (e) {
            FormSaque.form.submit();
        });
        
        pageSetUp();

        var pagefunction = function () {

            if (salvo) {
                $.smallBox({
                    title: "Saque realizado",
                    //content: "<i class='fa fa-clock-o'></i> <i></i>",
                    color: "#739e73",
                    iconSmall: "fa fa-check-circle fadeInRight animated",
                    timeout: 4000
                });
            }

            var $saqueForm = FormSaque.form.validate({
                rules: {
                    CB03_VALOR: {
                        required: true
                    },
                },
                messages: {
                    CB03_VALOR: {
                        required: 'Campo obrigatório'
                    },
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function () {
                    FormSaque.send('index.php?r=estabelecimento/global-crud&action=actionSaque', callbackSaque);
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
            <i class="fa-fw fa fa-money"></i> 
            Saque <span></span>
        </h1>
    </div>
</div>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

        <div role="content">

            <div class="widget-body no-padding">

                <form action="#" id="saque-form" class="smart-form" novalidate="novalidate" method="post">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="CB03_SAQUE_MIN" value="" />

                    <div class="row">
                        <section class="col col-3">Saldo atual
                            <label class="input"> <label class="icon-prepend">R$</label>
                                <input type="text" name="CB03_SAQUE_MAX" placeholder="" readonly="true" class="">
                            </label>
                        </section>
                        <section class="col col-3">Valor do saque
                            <label class="input"> <label class="icon-prepend">R$</label>
                                <input type="text" name="CB03_VALOR" placeholder="" class="">
                            </label>
                        </section>
                    </div>

                    <footer>
                        <button id="btn-sacar" type="button" class="btn btn-primary">
                            Solicitar saque
                        </button>
                    </footer>

                </form>

            </div>

        </div>

    </article>
</div>
<?php
}
?>