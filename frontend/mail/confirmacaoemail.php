<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Olá, Seja Bem Vindo ao aplicativo E$TALECAS.</h2>
<br>
<h3>Clique no botão abaixo para validar sua conta:</h3>
<?= Html::a('VALIDAR CONTA', ['api-empresa/validar-usuario', 'authKey' => $authKey], ['class' => 'btn btn-primary']) ?>
