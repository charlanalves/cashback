<?php
/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\SmartAdminAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

SmartAdminAsset::register($this);

//var_dump($this->params);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en-us">	
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">

        <!-- DEV links : turn this on when you like to develop directly -->
        <!--<link rel="stylesheet" type="text/css" media="screen" href="../Source_UNMINIFIED_CSS/smartadmin-production.css">-->
        <!--<link rel="stylesheet" type="text/css" media="screen" href="../Source_UNMINIFIED_CSS/smartadmin-skins.css">-->

        <!-- SmartAdmin RTL Support -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css"> 

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->

        <!--<link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">-->

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

        <!-- #GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- #APP SCREEN / ICONS -->
        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">
        <link rel="stylesheet" type="text/css" media="screen" href="css/style_detalhe_empresa.css">

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <script type="text/javascript">
            
            SYSTEM = {};
            var categoriaSelected = '';
            var itensCatSelected = [];
            
            document.addEventListener("DOMContentLoaded", function (event) {

                // exibe ou esconde os itens da categoria
                SYSTEM.itensCategoria = function () {
                    $('div#itens-categoria-all').toggle('fast');
                }

                // filtra empresas por categoria e seus itens
                SYSTEM.filtrarEmpresa = function () {

                    var categoria = $('select#filtro-categoria').val();
                    var item = $('#itens-categoria-all input:checked').map(function (_, el) {
                        itensCatSelected.push($(el).attr('title'));
                        return $(el).val();
                    }).get();

                    var busca = $.ajax({
                        url: 'index.php?r=empresa/filtra-empresas',
                        type: 'POST',
                        data: {'categoria': categoria, 'item': item},
                        dataType: "jsonp"
                    });

                    busca.always(function (data) {
                        retorno = data.responseText;
                        if (retorno) {

                            // esconde os itens da categoria
                            $('div#itens-categoria-all').hide('fast');

                            // exibe ou esconde itens filtrados
                            if (itensCatSelected.length && categoria) {
                                $('div#itens-categoria-selected i').text(itensCatSelected.join(', '));
                                $('div#itens-categoria-selected').show('fast');
                            } else {
                                $('div#itens-categoria-selected i').text('');
                                $('div#itens-categoria-selected').hide('fast');
                            }
                            itensCatSelected = [];

                            // exibe resultado do filtro
                            $('div.container').html(retorno);

                        } else {
                            $.smallBox({
                                title: "Opss",
                                content: "<i class='fa fa-clock-o'></i> <i>ocorreu um erro tente novamente...</i>",
                                color: "#C46A69",
                                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                timeout: 4000
                            });
                        }
                    });
                }
                
                SYSTEM.limparItensSelecionados = function () {
                    // desmarca itens selecionados do filtro
                    $('#itens-categoria-all input:checked').map(function (_, el) {
                        $(el).attr('checked', false);
                    });
                    SYSTEM.filtrarEmpresa();
                }
                
                SYSTEM.loadItensCategoria = function () {
                
                    $('div#ckeckbox-itens').html('');
                    if (!categoriaSelected) {
                        $('button#btn-itens-categoria').attr('disabled', true);

                    } else {
                        var itens = $.ajax({
                            url: 'index.php?r=empresa/itens-categoria',
                            type: 'POST',
                            data: {'categoria': categoriaSelected},
                            dataType: "jsonp"
                        });

                        itens.always(function (data) {
                            retorno = data.responseText;
                            if (retorno) {
                                // exibe itens da categoria no filtro
                                $('div#ckeckbox-itens').html(retorno);
                                $('button#btn-itens-categoria').attr('disabled', false);
                            }
                        });
                    }
                }

                $('#filtro-categoria').change(function (a) {
                    categoriaSelected = a.target.value;
                    if(!$("div#itens-categoria-all:hidden").length){
                        $('div#itens-categoria-all').toggle('fast');
                    }
                    SYSTEM.loadItensCategoria();
                });

            });
        </script>

    </head>

    <body class="smart-style-0 fixed-header">

        <!-- #MAIN PANEL -->
        <div id="main" role="main">

            <!-- #MAIN CONTENT -->
            <div class="container no-padding">
                <?= $content ?>
            </div>
            <!-- END #MAIN CONTENT -->

        </div>
        <!-- END #MAIN PANEL -->

        <!-- #PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white">

                    </span>
                </div>
            </div>
        </div>
        <!-- END FOOTER -->

        <!--================================================== -->

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->


        <!-- #PLUGINS -->

        <script src="js/libs/jquery-2.1.1.min.js"></script>

        <script src="js/libs/jquery-ui-1.10.3.min.js"></script>


        <script src="js/jquery.priceformat.min.js"></script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

        <!-- BOOTSTRAP JS -->
        <script src="js/bootstrap/bootstrap.min.js"></script>

        <!-- CUSTOM NOTIFICATION -->
        <script src="js/notification/SmartNotification.min.js"></script>

        <!-- JARVIS WIDGETS -->
        <script src="js/smartwidgets/jarvis.widget.min.js"></script>

        <!-- EASY PIE CHARTS -->
        <script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

        <!-- SPARKLINES -->
        <script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!-- JQUERY SELECT2 INPUT -->
        <script src="js/plugin/select2/select2.min.js"></script>

        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

        <!-- browser msie issue fix -->
        <script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

        <!-- FastClick: For mobile devices: you can disable this in app.js -->
        <script src="js/plugin/fastclick/fastclick.min.js"></script>

        <!--[if IE 8]>
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->

        <!-- Demo purpose only -->
        <!--<script src="js/demo.min.js"></script>-->

        <!-- MAIN APP JS FILE -->
        <script src="js/app.min.js"></script>

        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
        <script src="js/speech/voicecommand.min.js"></script>

        <!-- SmartChat UI : plugin -->
        <script src="js/smart-chat-ui/smart.chat.ui.min.js"></script>
        <script src="js/smart-chat-ui/smart.chat.manager.min.js"></script>

    </body>

</html>
