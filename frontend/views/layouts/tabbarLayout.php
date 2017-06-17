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
<style type="text/css">
	div.divTap {height: 100%;}
</style>
<?php $this->beginBody() ?>
<?php $this->endBody() ?>
<script>

	<?php
	/*
	Referência do script "layout tabbar ":

	Todo o script desenvolvido, deve ser armazenado dentro do objeto SYSTEM.

	Para inicializar a tela utilize o comando:

		var bootConfig = {
			tab:
				{add:[
					{id:"",label:""}, // id: id da tab, label: texto da tab
					{id:"",label:""}, // id: id da tab, label: texto da tab
				],
				active: "", // recebe o id da tab ativa
				btClose: false, // fechar tab, valor default: false
			},
		};

	SYSTEM.boot(bootConfig);

	// Para utilizar as tabs com CLICK:

		var myTabbar = SYSTEM.tab;
		myTabbar.attachEvent("onTabClick", function(id, last_id){
	        if (id == "aba1" && last_id != "aba1") {
	        	funcaoAoClicarNaAba1();

	        } else
	        if (id == "aba2" && last_id != "aba2") {
	        	funcaoAoClicarNaAba1();

	        } else
	        if (id == "aba3" && last_id != "aba3") {
	        	funcaoAoClicarNaAba3();

	        }
		});

	// Para criar layout dentro da tab:

		var tabLayout = new dhtmlXLayoutObject("idTab", "2E"); // deve passar o id da tab e o tipo de layout
		var T1 = rotaLayout.cells("a");
		var T2 = rotaLayout.cells("b");

		** Para visualizar as opcoes de layout: http://dhtmlx.com/docs/products/dhtmlxLayout/samples/02_conf/01_patterns.html

	*/
	?>

	var SYSTEM = (function(){

		var cesta = {

		};

		cesta.boot = function(config){
			SYSTEM.tab = loadTab(config.tab);
		}

		return cesta;
	})();

	function loadTab(tab){

		var outerLayout = new dhtmlXLayoutObject(document.body, "1C");
		var tela1 = outerLayout.cells('a');
		tela1.hideHeader();

		var myTabbar = new dhtmlXTabBar(document.body, "top");
		var divHtmlTab = '';

		//myTabbar.setImagePath("<?=\Yii::getAlias('@assetsPath');?>/layoutMask/imgs/");
		myTabbar.setImagePath("<?=\Yii::getAlias('@assetsPath');?>/dhtmlx/terrace/imgs/");
	    myTabbar.setAlign("center");

		// botao para fechar a tab
		if (tab.btClose === true) {
			myTabbar.enableTabCloseButton(true);
		}

		qtdAdd = Object.keys(tab.add).length;

		// monta as tabs
		for (var i = 0; qtdAdd > i; i++) {

			// cria tabbar (id, label, largura)
		    myTabbar.addTab(tab.add[i].id,tab.add[i].label,"*");

			// div para as tabs
		    divHtmlTab = divHtmlTab+'<div id="'+tab.add[i].id+'" class="divTap">'+tab.add[i].label+'</div>';

		}

		// cria elementos html (div para as tabs)
		tela1.attachHTMLString(divHtmlTab);

	    // vinculo do id da tab com o id da div
		for (var i = 0; qtdAdd > i; i++) {
			myTabbar.setContent(tab.add[i].id, tab.add[i].id);
		}

		// ativa tab
	    myTabbar.setTabActive(tab.active);

	    return myTabbar;

	}

</script>
<?= $content ?>
</body>
</html>
<?php $this->endPage() ?>
