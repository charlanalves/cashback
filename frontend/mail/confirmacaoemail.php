<?php
use yii\helpers\Html;
use yii\helpers\Url;

$url = '52.67.208.141/cashbackdev/frontend/web/index.php?r=api-empresa/validar-usuario&authKey='.$authKey;
?>
<h2>Olá, Seja Bem Vindo ao aplicativo E$TALECAS.</h2>

<h3>Clique no link abaixo para validar sua conta:</h3>

<?= '<a style="font-size: 20px;" href="'.$url.'">VALIDAR CONTA</a>' ?>
