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
class JqueryBuilderAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap.min.css',
        'mmsGenerator/css/bootstrap-select.min.css',
        'jqueryBuilder/css/query-builder.default.min.css',
    ];

    public $js = [
        'mmsGenerator/js/bootstrap-select.min.js',
        'jqueryBuilder/js/query-builder.standalone.js',
        'jqueryBuilder/i18n/query-builder.pt-BR.js',
        'jqueryBuilder/js/sql-parser.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
