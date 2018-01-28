<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

$this->title = 'Administrador';

use app\assets\CashBackAsset;
CashBackAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en-us">	
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

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
        <!--<link rel="stylesheet" type="text/css" media="screen" href="css/your_style2.css"> -->

        <style>
            .fixed-navigation nav>ul {
                width: 100%;
                overflow-x: hidden;
                overflow-y: hidden;
            }
            #main {
                margin-left: 180px;
                padding: 15px;
                padding-bottom: 52px;
                min-height: 500px;
                position: relative;
            }
            .fixed-header #main {
                margin-top: 49px;
            }
            #header>:first-child, aside {
                width: 180px;
            }
            .container {
                width: 100%;
            }
            .smart-form fieldset{
                padding: 10px 14px 5px!important;
            }
            label.error {
                color: red;
            }
            .dropzone {
                min-height: 155px!important;
            }
            
            /* CSS DX ---------------------------------------- */
            .dx-grid {
                position: relative;
                width: 100%;
                height: 100%;
            }
        </style>

        <script>
    
            document.addEventListener("DOMContentLoaded", function (event) {
                $("a#menu-empresa").click(function (e) {
                    document.location.href = 'index.php?r=administrador/empresa';
                    return false;
                });
                
                $("a#menu-representante").click(function (e) {
                    document.location.href = 'index.php?r=administrador/representante';
                    return false;
                });
                
                 $("a#menu-transferencias").click(function (e) {                   
                    document.location.href = 'index.php?r=administrador/transferencias';
                    return false;
                });
                
                 $("a#menu-categoria").click(function (e) {                   
                    document.location.href = 'index.php?r=administrador/categoria';
                    return false;
                });
                
                 $("a#menu-param-sistema").click(function (e) {                   
                    document.location.href = 'index.php?r=administrador/param-sistema';
                    return false;
                });
                
                // SAIR ----------------------------------------------------                
                $("#menu_sair").click(function (e) {
                    $.SmartMessageBox({
                        title: "Deseja sair?",
                        //content : "",
                        buttons: '[Não][Sim]'
                    }, function (ButtonPressed) {
                        if (ButtonPressed === "Sim") {
                            $('form[name=form-sair]').submit();
                        }
                    });
                    return false;
                });
                
            });

        </script>

    </head>


    <body class="fixed-header fixed-navigation ">
        <?php $this->beginBody() ?> 

        <header id="header">
            <div id="logo-group">
                <!-- PLACE YOUR LOGO HERE -->
                <span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>
                <!-- END LOGO PLACEHOLDER -->
            </div>
        </header>

        <aside id="left-panel">

            <nav>
                <ul>
                    <li class="">
                        <a href="#" title="Empresa" id="menu-empresa"><i class="fa fa-lg fa-fw fa-briefcase"></i> <span class="menu-item-parent">Empresa</span></a>
                    </li>
                    <li class="">
                        <a href="#" title="Representante" id="menu-representante"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">Representante</span></a>
                    </li>
                    <li class="">
                        <a href="#" title="Transferências" id="menu-transferencias"><i class="fa fa-exchange"></i> <span class="menu-item-parent">Transferências</span></a>
                    </li>
                    <li class="">
                        <a href="#" title="Categorias" id="menu-categoria"><i class="fa fa-flag"></i> <span class="menu-item-parent">Categorias</span></a>
                    </li>
                    <li class="">
                        <a href="#" title="Parâmetros do Sistema" id="menu-param-sistema"><i class="fa fa-cogs"></i> <span class="menu-item-parent">Config. Sistema</span></a>
                    </li>
                    <?=
                    Html::beginForm(['/administrador/logout'], 'post', ['name' => 'form-sair'])
                     . Html::endForm()
                     . '<li>'
                     . Html::a('<i class="fa fa-lg fa-fw fa-power-off"></i> Sair', '#', ['id' => 'menu_sair'])
                     . '</li>';
                    ?>
                </ul>
            </nav>


            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

        </aside>

        <!-- #MAIN PANEL -->
        <div id="main" role="main">

            <!-- #MAIN CONTENT -->
            <div class="container" id="main-container">
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
        
        <script src="js/plugin/jquery-block/jquery.blockUI.js"></script>

        <script src="js/jquery.priceformat.min.js"></script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

        <!-- JS DROPZONE -->
        <!--<script src="js/plugin/dropzone/dropzone.min.js"></script>--> 

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

        <!-- JQUERY MASKED MONEY -->
        <script src="js/plugin/masked-input/jquery.maskMoney.min.js"></script>

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
        <script src="js/global.js"></script>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>