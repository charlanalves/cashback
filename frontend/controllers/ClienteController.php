<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\CB10CATEGORIA;
use common\models\CB04EMPRESA;
use common\models\CB11ITEMCATEGORIA;
use common\models\SYS01PARAMETROSGLOBAIS;

/**
 * Empresa controller
 */
class ClienteController extends GlobalBaseController {
    
    public function actionConvidarAmigos() 
    {
        $user = \Yii::$app->user->identity;
     
        $this->layout = 'smartAdminLite';
        
        return $this->render('convidarAmigos', ['user' => $user]);
    }

    private static function optionsSelect($a, $primeiro = []) 
    {
        $r = ($primeiro) ? "<option value='" . array_keys($primeiro)[0] . "'>" . array_values($primeiro)[0] . "</option>\n" : '';
        foreach ($a as $v) {
            $r .= "<option value='" . $v['CB10_ID'] . "'>" . $v['CB10_NOME'] . "</option>\n";
        }
        return $r;
    }
   
}
