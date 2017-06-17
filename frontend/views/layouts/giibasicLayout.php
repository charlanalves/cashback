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
	<noscript>
		<meta http-equiv="Refresh" content="1;erroJavascript.php">
	</noscript>

</head>
<body>
<?php $this->beginBody() ?>
<?php $this->endBody() ?>
<script type="text/javascript">
</script>
<?= $content ?>
</body>
</html>
<?php $this->endPage() ?>
