<?php

use app\assets\MMSAsset;
use yii\helpers\Html;

MMSAsset::register($this);

?>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
    	<noscript>
    		<meta http-equiv="Refresh" content="1;erroJavascript.php">
    	</noscript>

        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php $this->endBody() ?>
        <script>
        	var SYSTEM = (function(){

        		var cesta = {};

        		cesta.boot = function(){
        			SYSTEM.Layout = loadLayout();
                    dhtmlx.image_path = "<?=\Yii::getAlias('@assetsPath');?>/dhtmlx/terrace/imgs/";
        			loadForm();
        		}

        		return cesta;
        	})();

        	function loadLayout(){
        		var outerLayout = new dhtmlXLayoutObject(document.body, "1C");
        		var innerLayout = outerLayout.cells("a").attachLayout("1C");

        		outerLayout.cells("a").hideHeader();
        		innerLayout.cells("a").hideHeader();

        		return {
        			innerLayout: innerLayout,
        			outerLayout: outerLayout,
        			tela: innerLayout.cells("a")
        		}
        	}

        	function loadForm()
        	{
        		SYSTEM.Form = SYSTEM.Layout.tela.attachForm();
        		SYSTEM.Form.setFocusOnFirstActive();
            }

        </script>
        <style>

			div.dhxcont_global_layout_area {
			    position: absolute;
			    left: 0px;
			    top: 0px;
			    background-color: #FFFFFF;
			    overflow: hidden;
			}

			div.dhxcont_global_content_area .dhxform_obj_dhx_terrace {
				width: 100% !important;
				top: auto !important;
				left: auto !important;
				bottom: auto !important;
				right: auto !important;
				overflow: hidden !important;
				display: flex;
				justify-content: center;
			}

        </style>
        <?= $content ?>
    </body>
</html>
<?php $this->endPage() ?>
