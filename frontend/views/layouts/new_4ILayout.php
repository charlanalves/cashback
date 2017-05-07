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
        	Referência do script:

        	Todo o script desenvolvido, deve ser armazenado dentro do objeto SYSTEM.

        	Para inicializar a tela utilize o comando:
        	* SYSTEM.boot();

        	Para acessar os objetos renderizados basta utilizar os seguintes caminhos dentro do objeto:
        	Layout:
        	* SYSTEM.Layout.outerLayout => para o layout externo onde se renderiza a toolbar e o InnerLayout.
        	* SYSTEM.Layout.innerLayout => para o layout interno, onde é renderizado o filtro ( cell id = 'a' ) e o grid ( cell id = 'b' )
        	* SYSTEM.Layout.innerLayout. telaCima => para a Célula superior.
        	* SYSTEM.Layout.innerLayout.telaMeioEsq => para a Célula central a esquerda.
            * SYSTEM.Layout.innerLayout.telaMeioDir => para a Célula central a direita.
            * SYSTEM.Layout.innerLayout.telaBaixo => para a Célula inferior.

        	Toolbar:
        	* SYSTEM.Toolbar.core => para o objeto DHTMLx da toolbar

            #################### Ferramentas do SYSTEM: ####################

        	>Layout:
            *Siga o exemplo para Manipulação de células
                SYSTEM.Layout.telaCima.hideHeader();
                SYSTEM.Layout.telaMeioEsq.hideHeader();
                SYSTEM.Layout.telaMeioDir.hideHeader();
                SYSTEM.Layout.telaBaixo.hideHeader();

        	*Siga o exemplo para definir larguras e alturas das células
                SYSTEM.Layout.larguraCell("a",400);
                SYSTEM.Layout.alturaCell("a",500);

            *Siga o exemplo para definir títulos das células
                SYSTEM.Layout.tituloCell("a","text");

        	>Toolbar:
        	* SYSTEM.Toolbar.icones( [icondeId1,iconeid2,...] ) => mostra os icones cujo ids estão na array passada como parâmetro
        	A lista de icones disponível está descrita no aquivo dhxtoolbar.xml que se encontra em /libs/layoutMask/dhxtoolbar.xml
        	* SYSTEM.Toolbar.titulo('teste') => modifica o titulo da toolbar

            ################################################################

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
        		var innerLayout = outerLayout.cells("a").attachLayout("4I");

        		innerLayout.setEffect('resize', true);
        		innerLayout.setEffect('collapse', true);

        		return{
        			innerLayout : innerLayout,
        			outerLayout : outerLayout,
        			telaCima : innerLayout.cells("a"),
        			telaMeioEsq : innerLayout.cells("b"),
                    telaMeioDir : innerLayout.cells("c"),
                    telaBaixo : innerLayout.cells("d"),

                    //Define largura e altura das células
                    larguraCell: function(cell, largura){
                        innerLayout.cells(cell).setWidth(largura);
                    },
                    alturaCell: function(cell, altura){
                        innerLayout.cells(cell).setHeight(altura);
                    },

                    //Define título das células
                    tituloCell: function(cell, titulo){
                        innerLayout.cells(cell).setText(titulo);
                        innerLayout.setCollapsedText(cell, titulo);
                    },
        		}
        	}

        	function loadToolbar(){
        		var toolbar = SYSTEM.Layout.outerLayout.cells("a").attachToolbar();
                //Declarando um método apto a ser sobrescrito sob necessidade, para trabalhar com os ícones da toolbar
                toolbar.doWithItem = function(itemId){};
        		toolbar.setIconsPath("<?=\Yii::getAlias('@assetsPath');?>/layoutMask/imgs/");
        		toolbar.loadXML("<?=\Yii::getAlias('@assetsPath');?>/layoutMask/dhxtoolbar.xml?etc=" + new Date().getTime());
        		toolbar.attachEvent("onXLE", function(){
        			toolbar.addSpacer("titulo");
        			toolbar.forEachItem(function(itemId){
        				toolbar.hideItem(itemId);
                        //Chamando o método genérico para cada item
	                    toolbar.doWithItem(itemId);
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
