<?php

    $this->beginBlock('cabecalho');
	    echo "<html>";
	    echo "<html lang='".Yii::$app->language."'>";	    
	    echo "<head>";
	    
	    $this->registerMetaTag(['charset' => Yii::$app->charset]);
	    $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
	    
	    echo \yii\helpers\Html::csrfMetaTags();
	    
	    \app\assets\MMSAsset::register($this);
	    
    $this->endBlock();
    
    
    
    $this->beginBlock('conteudo');   
	    $this->head();
	    echo "</head>";	
	    echo "<body>";
	      
	    $this->beginBody();
		    echo $conteudo;
		    //$this->registerJs("SYSTEM(".$this->layout.").boot();", View::POS_HEAD);
	      
	    $this->endBody();   
	    $this->endPage();
    $this->endBlock();
    

    
    $this->beginBlock('rodape');
     	echo "</body>";
     	echo "</html>";
    $this->endBlock();    
 
