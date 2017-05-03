<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\CB16PEDIDO;
use common\models\CB17PRODUTOPEDIDO;
use common\models\CB04EMPRESA;

/**
 * checkout controller
 */
class CheckoutController extends GlobalBaseController {

    private $user;

    public function __construct($id, $module, $config = []) {
        $this->user = \Yii::$app->user->identity;
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex() {
        $this->layout = 'smartAdminCheckout';
        $view = 'error';
        $param['msg'] = "Pedido inválido!";

        if (($pedido = \Yii::$app->request->get('pedido'))) {
            $dadosPedido = CB16PEDIDO::getPedido($pedido, $this->user->id);
            if ($dadosPedido) {
                $view = 'index';
                $param['data'] = self::dePedidoParaGateway(array_merge($dadosPedido, $this->user->attributes));
            }
        }
        return $this->render($view, $param);
    }

    private static function dePedidoParaGateway($data) {
        return [
            'valor_total' => $data['CB16_VALOR'] * (int)$data['CB16_NUM_PARCELA'],
            'item' => [[
                'item_cod' => $data['CB17_VARIACAO_ID'],
                'item_desc' => $data['CB17_NOME_PRODUTO'],
                'item_qtd' => $data['CB17_QTD'],
                'item_vlr' => $data['CB17_VLR_UNID'],
                'item_img' => $data['CB14_URL'],
            ]],
            'cod_transacao' => $data['CB16_ID'], // sera salvo o id do pedido na transacao do gateway
            'nome_loja' => $data['CB04_NOME'],
            'logo_loja' => $data['CB04_URL_LOGOMARCA'],
            'comprador_nome' => $data['name'],
            'comprador_cpf' => $data['cpf_cnpj'],
            'comprador_email' => $data['email'],
            'comprador_tel' => '',
        ];
    }

}