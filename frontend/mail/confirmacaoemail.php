<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

$url = '52.67.208.141/cashbackdev/frontend/web/index.php?r=api-empresa/validar-usuario&authKey='.$authKey;
?>
<h2>Olá, Seja Bem Vindo ao aplicativo E$TALECAS.</h2>
<br>
<h3>Clique no botão abaixo para validar sua conta:</h3>

<?= '<a href="'.$url.'">VALIDAR CONTA</a>' ?>
