<?php
use yii\helpers\Html;
use yii\helpers\Url;

$url = '52.67.208.141/cashbackdev/frontend/web/index.php?r=api-empresa/validar-usuario&authKey='.$authKey;
?>

Olá, Seja Bem Vindo ao aplicativo E$TALECAS.<br />
<p>
	Clique no link abaixo para validar sua conta: <?= '<a style="font-size: 20px;" href="'.$url.'">VALIDAR CONTA</a>' ?>
</p>

