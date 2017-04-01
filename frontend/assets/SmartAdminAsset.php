<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SmartAdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/smartadmin-production-plugins.min.css',
        'css/smartadmin-production.min.css',
        'css/smartadmin-skins.min.css',
        '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700',
        'css/site.css',


        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/smartadmin-production-plugins.min.css',
        'css/smartadmin-production.min.css',
        'css/smartadmin-skins.min.css',
        'css/smartadmin-rtl.min.css',
        'css/your_style.css',
    ];
    public $js = [
        'js/libs/jquery-2.1.1.min.js',
        'js/libs/jquery-ui-1.10.3.min.js',
        //'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js', // pagseguro producao
        'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js', // pagseguro teste
        'js/plugin/pace/pace.min.js',
        'js/app.config.js',
        'js/app.min.js',
        'js/plugin/jquery-touch/jquery.ui.touch-punch.min.js',
        'js/bootstrap/bootstrap.min.js',
        'js/notification/SmartNotification.min.js',
        'js/smartwidgets/jarvis.widget.min.js',
        'js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js',
        'js/plugin/sparkline/jquery.sparkline.min.js',
        'js/plugin/masked-input/jquery.maskedinput.min.js',
        'js/plugin/masked-input/jquery.maskMoney.min.js',
        'js/plugin/select2/select2.min.js',
        'js/plugin/bootstrap-slider/bootstrap-slider.min.js',
        'js/plugin/msie-fix/jquery.mb.browser.min.js',
        'js/plugin/fastclick/fastclick.min.js',
        'js/speech/voicecommand.min.js',
        'js/smart-chat-ui/smart.chat.ui.min.js',
        'js/smart-chat-ui/smart.chat.manager.min.js',
        'js/plugin/jquery-validate/jquery.validate.min.js',
        'js/global.js',
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_END];
       
}
