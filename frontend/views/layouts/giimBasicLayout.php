<?php

 $this->beginContent('@app/views/layouts/giimBaseLayout.php', ['conteudo' => $content]); 
 $this->endContent();

 echo $this->blocks['cabecalho'];
 echo $this->blocks['conteudo'];
 echo $this->blocks['rodape'];