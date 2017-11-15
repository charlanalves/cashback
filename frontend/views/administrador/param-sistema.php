

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-cogs"></i> 
            Parâmetros do Sistema<span></span>
        </h1>
    </div>
</div>


<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body">

                <form action="#" id="param-form" class="smart-form" novalidate="novalidate" method="post">
                    <fieldset>
                        <div class="row padding-top-15" id="campos-param">
                        </div>
                    </fieldset>
                    <footer>
                        <button id="btn-salvar" type="button" class="btn btn-primary">
                            Salvar
                        </button>
                        <button id="btn-reset" type="button" class="btn btn-default">
                            Restaurar parâmetros
                        </button>
                    </footer>
                </form>

            </div>

        </div>

    </article>
</div>

<script>

    var FormParam = {},
        camposHtml = "",
        paramDefault = {},
		pp = <?= $parans ?>;
		
		if (Array.isArray(pp)) {
			param = pp;
		}else {
			param = JSON.parse('<?= $parans ?>');
		}

        callbackSaveParam = function (data) {
            if (data.status == true) {
                message = 'Parâmetros salvos.';
                type = 'success';
                ico = 'check-circle';
            } else {
                if (typeof data.retorno != 'undefined'){
                    message = data.retorno;                        
                } else {
                    message = data.message;
                }
                type = 'danger';
                ico = 'frown-o';
            }
            Util.smallBox(message, '', type, ico);
        };

    document.addEventListener("DOMContentLoaded", function (event) {

        for (var i in param) {
            valor = param[i].SYS01_VALOR;
            camposHtml += '<section class="col col-6">' + param[i].SYS01_NOME;
            camposHtml += '<label class="textarea textarea-resizable"> <i class="icon-prepend fa fa-cog"></i>';
            camposHtml += '<textarea rows="6" class="custom-scroll" name="' + param[i].SYS01_COD + '">' + valor + '</textarea>';
            camposHtml += '</label>';
            camposHtml += '<div class="note">COD: ' + param[i].SYS01_COD + '</div>';
            camposHtml += '</section>';
            paramDefault[param[i].SYS01_COD] = valor;
        }

        $('div#campos-param').html(camposHtml);

        // obj form
        FormParam = new Form('param-form');

        $("#btn-salvar").click(function (e) {
            FormParam.form.submit();
        });
        
        $("#btn-reset").click(function (e) {
            FormParam.setFormData(paramDefault);
        });

        pageSetUp();

        var pagefunction = function () {
            var $paramForm = FormParam.form.validate({
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function () {
                    FormParam.send('index.php?r=administrador/global-crud&action=saveParamSistema', callbackSaveParam);
                }
            });
        };

        // Load form valisation dependency 
        loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);
   
   });
   
</script>