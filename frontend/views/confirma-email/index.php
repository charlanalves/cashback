<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <link rel="apple-touch-icon" sizes="57x57" href="indicacao/assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="indicacao/assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="indicacao/assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="indicacao/assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="indicacao/assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="indicacao/assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="indicacao/assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="indicacao/assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="indicacao/assets/img/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="indicacao/assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="indicacao/assets/img/favicons/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="indicacao/assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="indicacao/assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="indicacao/assets/img/favicons/manifest.json">
        <!--
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">
    -->
        <title>ESTALECAS</title>

        <link rel="stylesheet" href="indicacao/bower_components/font-awesome/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="indicacao/bower_components/framework7/dist/css/framework7.ios.min.css" type="text/css">
        <link rel="stylesheet" href="indicacao/bower_components/swipebox/src/css/swipebox.css" type="text/css">
        <link rel="stylesheet" href="indicacao/bower_components/owl-carousel/owl-carousel/owl.carousel.css" type="text/css">
        <link rel="stylesheet" href="indicacao/bower_components/owl-carousel/owl-carousel/owl.theme.css" type="text/css">

        <link rel="stylesheet" href="indicacao/assets/css/app.css" type="text/css">
        <!--<link rel="stylesheet" href="indicacao/assets/themes//style.css" id="theme-style">-->

        <style>
            .negrito {
                font-weight: bold;
            }
            .navbar:after {
                height: 0px;
            }
            
            .pages, .pages input, .pages select, .pages textarea, .pages option {
                color: #000 !important;
            }
            
            .list-block {
                margin: 5px 0;
            }

            .buttonTabProduct {
                min-height: 32px;
                line-height: 30px;
            }
            
            select, input {
                padding: 0 10px!important;    
                font-size: 16px;
            }
            
            .button-valid-1 {background: #30b8ff!important;}
            .button-valid-2 {background-color: #175779!important;}
            .button-valid-3 {background-color: #0f3c54!important;}
            .button-valid-1,.button-valid-2,.button-valid-3 {
                border: 0px!important;
                width: 100%;
                font-weight: bold;
                font-size: 18px;
                margin: 2px 5px!important;
                right: 8px;
            }

            /* CSS TAB BAR "COMPRAR" ---------------------------------------- */
            #tabbar-buy div[class*="col-"] {
                text-align: center;
                color: #FFF;
                padding: 5px 0px;
                font-size: 11px;
            }
            #tabbar-buy div[class*="col-"] strong {
                font-size: 22px;
                 display:block; 
                 clear:both
            }
            
            /* CSS FILTRO --------------------------------------------------- */
            #page-filter .swiper-wrapper {
                margin: 5px;
            }
            #page-filter .swiper-slide {
                color:#000;
                background: #FFF;
                box-sizing: border-box;
                border: 1px solid #ccc;
            }
            #page-filter .swiper-slide label {
                text-align:center;
                display:block;
                padding-top: 20px;
                padding-bottom: 100%;
                font-size:16px;
            }
            #page-filter .swiper-slide label div {
                text-align:center;
                width: 100%;
            }
            #page-filter .swiper-slide label div.item-inner {
                padding-top: 35px;
            }
            #page-filter .swiper-container {
                height: 120px;
                margin: 5px;
            }
            #page-filter .swiper-slide label.label-radio input[type=radio]:checked~.item-inner {
                background-size: 32px;
                background-position: center top;
            }
            #page-filter .swiper-slide label.label-radio input[type=radio]~.item-inner {
                padding-right: 0px;
            }
            
            .navbar-fixed .page-content, .navbar-through .page-content {
                padding-top: 35px;        
            }
            
            .list-block .item-inner:after{
                height: 0px;
            }
            
            .link-tabbar-bottom i{
                font-size: 28px;
            }
            .link-tabbar-bottom{
                color: #666!important;
                width: 20%!important;
            }
            
            .link-tabbar-bottom.active{
                color: #FFF!important;
                background-color: #be0000;
            }
            
            .link-tabbar-bottom img {
                max-width: inherit; 
                width: 71px!important;
            }
            
            .infinite-scroll-preloader {
                margin-top: 20px;
                margin-bottom: 20px;
                text-align: center;
            }
            .infinite-scroll-preloader .preloader {
                width:34px;
                height:34px;
            }   
        </style>

    </head>
    <body class="" style="max-width: 450px;margin: 0 auto;">

        <div class="statusbar-overlay"></div>
        <div class="panel-overlay"></div>

        <!-- Views -->
        <div class="views">
            
            <div class="view view-main">
                <div class="navbar navbar-clear">
                    <div class="navbar-inner">
                        <div class="center sliding"></div>
                    </div>
                </div>

                <div class="pages navbar-fixed toolbar-fixed">
                    <div data-page="registration" class="page">

                        <div class="page-content">

                            <div class="nice-header header-fix-top small">
                                <div class="logo">
                                    <h1>E$TALECAS</h1>
                                    <h2>Seu dinheiro de volta</h2>
                                </div>
                                <svg class="anim-svg" viewBox="0 0 629 63" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                    <defs></defs>
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                        <g id="Splash" sketch:type="MSArtboardGroup" transform="translate(-60.000000, -749.000000)" fill="#FFFFFF">
                                            <g id="flaga" sketch:type="MSLayerGroup">
                                                <path d="M60.7617187,750.025391 L375.435547,811.568359 L688.558594,749.867188 L60.7617187,750.025391 Z" id="Path-30" sketch:type="MSShapeGroup"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>

                            <div class="login-view-box mt-50 text-center">
                                <h2>
                                    <?= ($conf) ? 'Obrigado por confirmar seu e-mail: <strong>' . $email . '</strong>':'Não foi possível confirmar o e-mail, tente novamente.' ?>
                                </h2>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="indicacao/bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/swipebox/src/js/jquery.swipebox.min.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/framework7/dist/js/framework7.min.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/Tweetie/tweetie.min.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/chartjs/Chart.js"></script>
        <script type="text/javascript" src="indicacao/bower_components/scrollAnimate/jquery.scrollAnimate.js"></script>
        <script src="indicacao/bower_components/owl-carousel/owl-carousel/owl.carousel.min.js"></script>

        <script type="text/javascript" src="indicacao/assets/js/jflickrfeed.min.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/min/app.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/animations.js"></script>

        <!-- Complementares -->
        <script type="text/javascript" src="indicacao/assets/js/jquery-block/jquery.blockUI.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/masked-input/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/masked-input/jquery.maskMoney.min.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/appExt.js"></script>
        <script type="text/javascript" src="indicacao/assets/js/global.js"></script>
        
    </body>
</html>


