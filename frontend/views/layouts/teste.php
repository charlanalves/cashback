<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\MMSAsset;
MMSAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
        <?= $content ?>

<?php $this->endBody() ?>

<?php $this->endPage() ?>
