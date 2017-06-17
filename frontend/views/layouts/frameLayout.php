<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\FrameAsset;
use yii\helpers\Html;

FrameAsset::register($this);

?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<noscript>
		<meta http-equiv="Refresh" content="1;erroJavascript.php">
	</noscript>

    <?php $this->head() ?>
</head>
<body class="skin-red fixed" data-spy="scroll" data-target="#scrollspy">
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>

<script type="text/javascript">
/*
    $(window).on('beforeunload', function (){
        return "A página atual será finalizada.";
    });
    $(window).on('unload', function (){
        window.open('index.php?r=Seguranca/login/unload')
    });
    */
</script>
</body>
</html>
<?php $this->endPage() ?>
