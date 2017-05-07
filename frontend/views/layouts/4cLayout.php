<?php

/* @var $this \yii\web\View */
/* @var $content string */

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
			//loadFiltro();
			SYSTEM.Toolbar  = loadToolbar();
		}

		return cesta;
	})();

	function loadLayout(){
		var outerLayout = new dhtmlXLayoutObject(document.body, "1C");
		var innerLayout = outerLayout.cells("a").attachLayout("3L");
        // pega a celula B do 3L e transforma em outras 2 celulas
        var dirCimaLayout = innerLayout.cells("b").attachLayout("2E");

        // estrutura do 3L
        innerLayout.cells("a").hideHeader();
		innerLayout.cells("b").hideHeader();
		innerLayout.cells("c").hideHeader();

        dirCimaLayout.cells("a").hideHeader();
		dirCimaLayout.cells("b").hideHeader();

		innerLayout.setEffect('resize', true);
		innerLayout.setEffect('collapse', true);
		return{
			innerLayout : innerLayout,
			outerLayout : outerLayout,
			telaEsq      : innerLayout.cells("a"),
			telaDirCima  : dirCimaLayout.cells("a"),
			telaDirMeio  : dirCimaLayout.cells("b"),
			telaDirBaixo : innerLayout.cells("c"),
			tamanho: function(tamanhoA,tamanhoB,tamanhoC,tamanhoD){//,tamanhoD
				innerLayout.cells("a").setWidth(tamanhoA);
				dirCimaLayout.cells("a").setWidth(tamanhoB);
                dirCimaLayout.cells("b").setWidth(tamanhoC);
                innerLayout.cells("c").setWidth(tamanhoD);

			},
			t1: function(titulo){
				innerLayout.cells("a").setText(titulo);
				innerLayout.setCollapsedText("a", titulo);

			},
			t2: function(titulo){
				dirCimaLayout.cells("a").showHeader();
				dirCimaLayout.cells("a").setText(titulo);
				dirCimaLayout.setCollapsedText("a", titulo);

			},
            t3: function(titulo){
				dirCimaLayout.cells("b").showHeader();
				dirCimaLayout.cells("b").setText(titulo);
				dirCimaLayout.setCollapsedText("b", titulo);

			},
            t4: function(titulo){
				innerLayout.cells("c").showHeader();
				innerLayout.cells("c").setText(titulo);
                innerLayout.setCollapsedText("c", titulo);

			},
		}
	}
/*
	function loadFiltro(){
		SYSTEM.Filtro = SYSTEM.Layout.innerLayout.cells("a").attachForm();
	}
    */

	function loadToolbar(){
		var toolbar = SYSTEM.Layout.outerLayout.cells("a").attachToolbar();
		toolbar.setIconsPath("<?=\Yii::getAlias('@assetsPath');?>/layoutMask/imgs/");
		toolbar.loadXML("<?=\Yii::getAlias('@assetsPath');?>/layoutMask/dhxtoolbar.xml?etc=" + new Date().getTime());
		toolbar.attachEvent("onXLE", function(){
			toolbar.addSpacer("titulo");
			toolbar.forEachItem(function(itemId){
				toolbar.hideItem(itemId);
			});
		});
		return {
			core: toolbar,
			icones: function(iconsIds){
				setTimeout(function(){
					for(var i = 0; iconsIds.length > i ;i++){
						toolbar.showItem(iconsIds[i]);
					}
				}, 1000);
			},
			titulo: function (titulo){
	            setTimeout(function(){
					toolbar.showItem('titulo');
					toolbar.setItemText('titulo', titulo);
				}, 1000);
			}
		}
	}

</script>
<?= $content ?>


</body>
</html>
<?php $this->endPage() ?>
