<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\CB10CATEGORIA;
use common\models\CB04EMPRESA;
use common\models\CB11ITEMCATEGORIA;
use common\models\CB15LIKEEMPRESA;
use common\models\CB16PEDIDO;
use common\models\CB17PRODUTOPEDIDO;
use common\models\CB05PRODUTO;
use common\models\CB06VARIACAO;

/**
 * Empresa controller
 */
class EmpresaController extends GlobalBaseController {

    private $user;

    public function __construct($id, $module, $config = []) {
        $this->user = \Yii::$app->user->identity;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex() {
        
        $this->layout = 'smartAdminEmpresa';
        $idUser = $this->user->id;
        $layout = $data = [];
        
        // saldo do cliente
        $layout['saldo'] = VIEWEXTRATOCLIENTE::saldoAtualByCliente($idUser);

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
    
    /*
     * Detalhe da empresa
     */
    public function actionDetalhe($empresa) {
        $this->layout = 'smartAdminEmpresaDetalhe';
        $dados = CB04EMPRESA::getEmpresa($empresa, $this->user->id);
       
        
        return $this->render(($dados) ? 'detalhe_motel' : 'error', ['empresa' => $dados]);
    }
    
    /*
     * Like empresa
     */
    public function actionLike($estabelecimento) {
        $CB15LIKEEMPRESA = new CB15LIKEEMPRESA();
        if($like = $CB15LIKEEMPRESA->findOne(['CB15_EMPRESA_ID' => $estabelecimento, 'CB15_USER_ID' => $this->user->id])){
            $like->delete();
            
        } else {
            $CB15LIKEEMPRESA->setAttributes(['CB15_EMPRESA_ID' => $estabelecimento, 'CB15_USER_ID' => $this->user->id]);
            $CB15LIKEEMPRESA->save();
            
        }
        return ($like || $CB15LIKEEMPRESA || false);
    }
    
    public function actionSavePedido() {
        
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {

            $get = \Yii::$app->request->get();
            
            if(($produto = $get['produto']) && ($variacao = $get['variacao'])) {

                $dadosP = CB05PRODUTO::findOne(['CB05_ID' => $produto]);
                $dadosV = CB06VARIACAO::findOne(['CB06_ID' => $variacao]);

                if($dadosP && $dadosV) {
                
                    $dadosP = $dadosP->attributes;
                    $dadosV = $dadosV->attributes;
                    
                    // salva pedido
                    $pedido = new CB16PEDIDO();
                    $pedido->CB16_EMPRESA_ID = $dadosP['CB05_EMPRESA_ID'];
                    $pedido->CB16_USER_ID = $this->user->id;
                    $pedido->CB16_VALOR = $dadosV['CB06_PRECO'];
                    $pedido->save();
                    $idPedido = $pedido->CB16_ID;

                    // salva itens pedido
                    $produtoPedido = new CB17PRODUTOPEDIDO();
                    $produtoPedido->CB17_PEDIDO_ID = $idPedido;
                    $produtoPedido->CB17_PRODUTO_ID = $dadosP['CB05_ID'];
                    $produtoPedido->CB17_NOME_PRODUTO =  $dadosP['CB05_TITULO'] . ' - ' . $dadosV['CB06_DESCRICAO'];
                    $produtoPedido->CB17_VLR_UNID = $dadosV['CB06_PRECO'];
                    $produtoPedido->CB17_VARIACAO_ID = $dadosV['CB06_ID'];
                    $produtoPedido->CB17_QTD = 1;
                    $produtoPedido->save();

                    $transaction->commit();
                    return $idPedido;
                }
            }
            return false;
            
        } catch (\Exception $exc) {
            $transaction->rollBack();
            var_dump($exc->getMessage());
            return;   
        }
        
    }
    
    protected static function formatDataForm($dados) {
        $newData = [];
        foreach ($dados as $v) {
            $newData[$v['name']] = $v['value'];
        }
        return $newData;
    }
}
