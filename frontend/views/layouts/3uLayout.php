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

	<?php
	/*
	Referência do script "layout toolbar+filtro+grid ":

	Todo o script desenvolvido, deve ser armazenado dentro do objeto SYSTEM.

	Para inicializar a tela utilize o comando:
	* SYSTEM.boot();


	Para acessar os objetos renderizados basta utilizar os seguintes caminhos dentro do objeto:
	Layout:
	* SYSTEM.Layout.outerLayout => para o layout externo onde se renderiza a toolbar e o InnerLayout.
	* SYSTEM.Layout.innerLayout => para o layout interno, onde é renderizado o filtro ( cell id = 'a' ) e o grid ( cell id = 'b' )
	* SYSTEM.Layout.innerLayout.telaCima => para a celula do filtro.
	* SYSTEM.Layout.innerLayout.telaBaixo => para a celula do grid.

	Filtro:
	* SYSTEM.Filtro => para o form renderizado dentro da aba filtro.

	Toolbar:
	* SYSTEM.Toolbar.core => para o objeto DHTMLx da toolbar

	Ferramentas do SYSTEM:

	Layout:
	* SYSTEM.Layout.t1("string") => para mudar o titulo da primeira celula, a do filro.
	* SYSTEM.Layout.t2("string") => para mudar o titulo da segunda celula, a do grid.

	Toolbar:
	* SYSTEM.Toolbar.icones( [icondeId1,iconeid2,...] ) => mostra os icones cujo ids estão na array passada como parâmetro
	A lista de icones disponível está descrita no aquivo dhxtoolbar.xml que se encontra em /libs/layoutMask/dhxtoolbar.xml
	* SYSTEM.Toolbar.titulo('teste') => modifica o titulo da toolbar

	*/
	?>

	var SYSTEM = (function(){

		var cesta = {};

		cesta.boot = function(){
			SYSTEM.Layout = loadLayout();
            dhtmlx.image_path = "<?=\Yii::getAlias('@assetsPath');?>/dhtmlx/terrace/imgs/";
			SYSTEM.Toolbar  = loadToolbar();
		}

		return cesta;
	})();

	function loadLayout(){
		var outerLayout = new dhtmlXLayoutObject(document.body, "1C");
		var innerLayout = outerLayout.cells("a").attachLayout("3U");

		outerLayout.cells("a").hideHeader();
		innerLayout.cells("b").hideHeader();
		innerLayout.cells("c").hideHeader();
		innerLayout.setEffect('resize', true);
		innerLayout.setEffect('collapse', true);
		return{
			innerLayout : innerLayout,
			outerLayout : outerLayout,
			telaEsq   : innerLayout.cells("a"),
			telaDir   : innerLayout.cells("b"),
			telaBaixo : innerLayout.cells("c"),
			tamanho: function(tamanhoA,tamanhoB,tamanhoC){//,tamanhoD
				innerLayout.cells("a").setWidth(tamanhoA);
				innerLayout.cells("b").setWidth(tamanhoB);
                innerLayout.cells("c").setWidth(tamanhoC);

			},
			t1: function(titulo){
				innerLayout.cells("a").setText(titulo);
				innerLayout.setCollapsedText("a", titulo);

			},
			t2: function(titulo){
				innerLayout.cells("b").showHeader();
				innerLayout.cells("b").setText(titulo);
				innerLayout.setCollapsedText("b", titulo);

			},
            t3: function(titulo){
				innerLayout.cells("c").showHeader();
				innerLayout.cells("c").setText(titulo);
				innerLayout.setCollapsedText("c", titulo);

			}
		}
	}

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
