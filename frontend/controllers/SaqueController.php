<?php

namespace frontend\controllers;

use common\controllers\GlobalBaseController;
use common\models\VIEWEXTRATOCLIENTE;
use common\models\CB03CONTABANC;
use common\models\SYS01PARAMETROSGLOBAIS;

/**
 * Saque controller
 */
class SaqueController extends GlobalBaseController {
    
    private $user;
    private $saldoAtual;
    private $saqueMax;
    private $saqueMin;

    public function __construct($id, $module, $config = []) {
        $this->user = \Yii::$app->user->identity;
        $this->saldoAtual = VIEWEXTRATOCLIENTE::saldoAtualByCliente($this->user->id);
        $this->saqueMax = (float) $this->saldoAtual;
        $this->saqueMin = (float) SYS01PARAMETROSGLOBAIS::getValor('2');
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex() {
        
        $this->layout = 'smartAdminSaque';
        $idUser = $this->user->id;
        $solicitacaoCriada = false;
        
        \Yii::$app->view->params = [
            'saldo' => $this->saldoAtual,
            'saqueMin' => $this->saqueMin,
            'saqueMax' => $this->saqueMax,
        ];

        
        $contaBancariaCliente = CB03CONTABANC::findOne(['CB03_CLIENTE_ID' => $idUser]);
        $dadosSaque = ($contaBancariaCliente) ? : new CB03CONTABANC();
        $dadosSaque->setAttribute('CB03_VALOR', '');
            
        $dadosSaque->scenario = CB03CONTABANC::SCENARIO_SAQUE;

        if (($formData = \Yii::$app->request->post())) {

            $dadosSaque->load($formData);
            $dadosSaque->setAttribute('CB03_CLIENTE_ID', $idUser);
            $dadosSaque->setAttribute('CB03_SAQUE_MIN', $this->saqueMin);
            $dadosSaque->setAttribute('CB03_SAQUE_MAX', $this->saqueMax);

            if ($dadosSaque->validate()) {
                
                $dadosSaque->save(false);
                $solicitacaoCriada = true;
                
//                var_dump($contaBancariaCliente->oldAttributes);
//                var_dump($dadosSaque);
//                var_dump('ok');
                
            } else {
                // formata valor moeda REAL
                $dadosSaque->setAttribute('CB03_VALOR', (string) \Yii::$app->u->moedaReal($dadosSaque->attributes['CB03_VALOR']));
            }

        }
        return $this->render('solicitacao', ['conta_bancaria' => $dadosSaque, 'solicitacao_criada' => $solicitacaoCriada]);
    }

}
