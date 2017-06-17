<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CashBackAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	
    public $css = [
		'dxassets/dhtmlx/terrace/dhtmlx_telas_sistema.css',		
		'dxassets/custom_scroll/customscroll.css',        
    ];
    public $js = [
		'dxassets/dhtmlx/terrace/dhtmlx.js',
		'dxassets/blockUI/jquery.blockUI.js',		
		'dxassets/custom_scroll/customscroll.js',
		'dxassets/jquery/lib.js',
    ];
   
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
