<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\CB10CATEGORIA;
use common\models\CB04EMPRESA;
use common\models\CB11ITEMCATEGORIA;

/**
 * Empresa controller
 */
class EmpresaController extends GlobalBaseController {

    public function actionIndex() {

        $this->layout = 'smartAdminEmpresa';
        $cliente = 1;


        $layout = $data = [];

        // saldo do cliente
        $layout['saldo'] = VIEWEXTRATOCLIENTE::saldoAtualByCliente($cliente);

        // categorias ativas
        $layout['categorias'] = self::optionsSelect(CB10CATEGORIA::findAll(['CB10_STATUS' => 1]), [''=>'Todas as categorias']);

        \Yii::$app->view->params = $layout;
        
        $empresas = CB04EMPRESA::getEmpresas();
        
        return $this->render('lista', ['empresas' => $empresas]);
    }

    public function actionFiltraEmpresas() {
        $post = \Yii::$app->request->post();
        $empresas = CB04EMPRESA::getEmpresas($post);
        return $this->renderPartial('lista', ['empresas' => $empresas]);
    }
    
    public function actionItensCategoria() {
        $categoria = \Yii::$app->request->post('categoria');
        $itens = CB11ITEMCATEGORIA::find()->where('CB11_CATEGORIA_ID = ' . (int) $categoria)->orderBy('CB11_DESCRICAO')->all();
        return $this->renderPartial('itens', ['itens' => $itens]);
    }
    
    private static function optionsSelect($a, $primeiro = []) {
        $r = ($primeiro) ? "<option value='" . array_keys($primeiro)[0] . "'>" . array_values($primeiro)[0] . "</option>\n" : '';
        foreach ($a as $v) {
            $r .= "<option value='" . $v['CB10_ID'] . "'>" . $v['CB10_NOME'] . "</option>\n";
        }
        return $r;
    }

}
