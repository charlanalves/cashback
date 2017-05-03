<?php

namespace common\modules\pagamento\controllers;

use common\controllers\GlobalBaseController;
use common\modules\pagamento\models\Pag01Transacao;
use common\modules\pagamento\models\Pag02ItemTransacao;

class PagamentoController extends GlobalBaseController
{
    public $enableCsrfValidation;
    private $data = [];    
//    function __construct($param = array()) {
//        
//        if($param){
//            $this->data['id'] = uniqid('pag_'.date('Ymd').'_');
//            foreach($param as $key => $value) {
//                $this->data[$key] = $value;
//            }
//        }
//    }
    
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $this->enableCsrfValidation = false;
        /*
         * @todo testa campos obrigatorios
         * item array: key['item_cod','item_desc','item_qtd','item_vlr']
         * gateway string: pagseguro
         * forma_pag string: CreditCard || OnlineDebit || Boleto 
         * valor_total float
         */
        
        $dados = (!empty(($post = \Yii::$app->request->post('dados')))) ? $post : [];
        
        $model = new Pag01Transacao(['scenario' => Pag01Transacao::SCENARIO_OPENCHECKOUT]);
        $model->setAttributes(array_change_key_case($dados, CASE_UPPER));
        
        if ($model->validate()) {
            return $this->renderPartial('index',['dados'=> $dados]);
            
        } else {
            $erro = "";
            if(is_array($model->errors))
                foreach ($model->errors as $v) {
                    $erro .= "&bull; " . implode('<br />', $v);
                }
            return $this->renderPartial('error',['dados'=> $erro]);
        }

    }
    
    /**
     * @inheritdoc
     */
    public function getPagamento()
    {
        return $this->data;
    }
    
    public function actionGateway()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try{
            //set data
            $this->setPagamento(\Yii::$app->request->post('dados'));
            
            //CASE_UPPER para o DB
            $dataDB = array_change_key_case($this->data, CASE_UPPER);
            
            // registra transacão (DB)
            $transacao = new Pag01Transacao();
            $transacao->setAttributes($dataDB);
            $transacao->save();
            $ID_TRANSACAO = $transacao->ID_TRANSACAO;
            
            // registra itens da transacão (DB)
            foreach($dataDB['ITEM'] as $col) {
                $item = new Pag02ItemTransacao();
                $item->setAttributes(array_change_key_case($col, CASE_UPPER));
                $item->ID_TRANSACAO = $ID_TRANSACAO;
                $item->save();
            }

            // registra transacão (Geteway)
            // @todo tratar retorno de erro, exibir o que esta faltando
            $retornoGateway = \Yii::$app->pagamentoComponent->{$this->data['gateway'].$this->data['forma_pag']}($this->data);
            var_dump($retornoGateway);
            var_dump(json_encode($retornoGateway));
            
            if($retornoGateway['status']){
//                $transacao->TOKEN_GATEWAY = $retornoGateway['return']->code;
//                $transacao->save();
                
            } else {
                throw new \Exception('Erro no checkout');
                
            }
            
            $transaction->commit();
                
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
		
    }

    /*
     * setPagamento
     * Formata dados recebidos do formulario
     * @todo validar campos obrigatorios para fazer o checkout
     */
    private function setPagamento($dados) 
    {
        $this->data['cod_transacao'] = (!empty($dados['cod_transacao'])) ? $dados['cod_transacao'] : uniqid('pag_'.date('Ymd').'_');
        foreach($dados as $v)
            if($v['name'] == 'item')
                foreach($v['value'] as $k => $vv){
                    $vv = array_values($vv);
                    $this->data[$v['name']][$k]['item_qtd'] = $vv[0];
                    $this->data[$v['name']][$k]['item_cod'] = $vv[1];
                    $this->data[$v['name']][$k]['item_desc'] = $vv[2];
                    $this->data[$v['name']][$k]['item_vlr'] = $vv[3];
                }
            else if(!empty($v['name']) && !empty($v['value']))
                $this->data[$v['name']] = $v['value'];
        
        // parcelas do cartao
        if(!empty($this->data['cartao_parcela']) && !empty($this->data['cartao_num_parcela']) && !empty($this->data['cartao_vlr_parcela'])){
            $cp = explode('-', $this->data['cartao_parcela']);
            $this->data['cartao_num_parcela'] = $cp[0];
            $this->data['cartao_vlr_parcela'] = $cp[1];
        }
            
        // telefone
        $this->data['comprador_tel_ddd'] = substr($this->data['comprador_tel'], 1, 2);
        $this->data['comprador_tel_numero'] = substr($this->data['comprador_tel'], 5);
        unset($this->data['comprador_tel']);
        
        // @todo necessario implementar no formulario
        $this->data['comprador_data_nascimento'] = '01/01/1985';
        //$this->data['cartao_nome'] = 'Comprador D Teste';
        $this->data['endereco_pais'] = 'BRA';
        //$this->data['hash_recebedor_secundario'] = 'PUBF5E6A73B00C64677928A7CEE036481A9'; // Vendedor 2
        
    }

}
