<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\gii\components\ActiveField;
use yii\gii\CodeFile;

$this->title = $generator->getName();

$templates = [];
foreach ($generator->templates as $name => $path) {
    $templates[$name] = "$name ($path)";
}

if (isset($results)) {
      $filesResult = $this->render('view/results', [
      'generator' => $generator,
      'results' => $results,
      'hasError' => $hasError,
            ], true);
     echo json_encode(['html' => $filesResult, 'preview' => false ]);
} elseif (isset($files)) {
    
      $filesResult =  $this->render('view/files', [
	      'id' => $id,
	      'generator' => $generator,
	      'files' => $files,
	      'answers' => $answers,
      ], true);
      
      echo json_encode(['html' => $filesResult, 'preview' => true ]);
}
?>