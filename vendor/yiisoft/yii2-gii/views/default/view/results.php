<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\Generator */
/* @var $results string */
/* @var $hasError boolean */
?>
<div class="default-view-results">

    <? if ($hasError == true) : ?>
         <div class="alert alert-danger">
         	Aconteceu um erro ao efetuar a geração:
         </div>
    <? endif;?>   
    
    <? if ($hasError == false) : ?>
 		<?php $ctrlUrl = './index.php?r='.$generator->controllerAction; ?>
        <div class="alert alert-success"><?= $generator->successMessage($ctrlUrl) ?></div>
    <? endif;?>    

   <pre><?= nl2br($results) ?></pre>  
</div>
