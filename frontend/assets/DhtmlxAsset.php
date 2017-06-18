<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class DhtmlxAsset extends AssetBundle
{
    public $basePath = '@vendor';
    public $baseUrl = '@assetsPath';
	
    public $css = [
//		'js/dhtmlx/terrace/dhtmlx.css',
		'js/dhtmlx/terrace/dhtmlx_telas_sistema.css?a=aasdasd',
    ];
    
    public $js = [
		'js/dhtmlx/terrace/dhtmlx.js',
		'js/dhtmlx/terrace/dhtmlxExtMMS.js',
		'js/blockUI/jquery.blockUI.js',
		'js/lib.js',
    ];
   
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}