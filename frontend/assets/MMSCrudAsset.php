<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;


/**
 * @author Charlan Santos
 */
class MMSCrudAsset extends AssetBundle
{
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap.min.css',
        'mmsGenerator/css/main.css?o=q',
        'dxassets/dhtmlx/terrace/dhtmlx_telas_sistema.css',
    ];
    
    public $js = [    
		'mmsGenerator/js/main.js?a=1',
        'mmsGenerator/js/builder.js?a=2',
    	'mmsGenerator/js/builderUtil.js?a=1',
        'mmsGenerator/js/builderObj.js?a=8',
        'dxassets/dhtmlx/terrace/dhtmlx.js?a=1',
        'mmsGenerator/js/UtilDhtmlxComponents.js?b=9',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset', 
        'app\assets\JqueryBuilderAsset',
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
