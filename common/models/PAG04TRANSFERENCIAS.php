<?php

namespace common\models;

use Yii;
use common\models\base\PAG04TRANSFERENCIAS as BasePAG04TRANSFERENCIAS;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class PAG04TRANSFERENCIAS extends BasePAG04TRANSFERENCIAS
{
    const M2E = 'M2E';
    const E2ADQ = 'E2ADQ';
    const E2C = 'E2C';
    const E2ADM = 'E2ADM';
    const V2B = 'V2B';
    const B2V = 'B2V';
    const M2SC = 'M2SC';
    const C2E = 'C2E';
    const M2C = 'M2C';
    const E2M = 'E2M';
     
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_DT_PREV', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_VLR', 'PAG04_TIPO'], 'required'],
            [['PAG04_ID_PEDIDO', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            [['PAG04_TIPO'], 'string', 'max' => 5],
            
            
        ]);
    }

    
    
    public static function getTransSaques()
    {
        
        $sql = "
			SELECT PAG04_TRANSFERENCIAS.PAG04_ID, CB02_CLIENTE.CB02_COD_CONTA_VIRTUAL AS receiver_id, PAG04_TRANSFERENCIAS.PAG04_VLR * 100 AS amount_cents
			FROM PAG04_TRANSFERENCIAS
			JOIN user ON user.id = PAG04_TRANSFERENCIAS.PAG04_ID_USER_CONTA_ORIGEM
			JOIN CB02_CLIENTE  ON CB02_CLIENTE.CB02_ID = user.id_cliente
			WHERE PAG04_TRANSFERENCIAS.PAG04_DT_DEP IS NULL AND PAG04_TRANSFERENCIAS.PAG04_TIPO = 'C2B'
			
			UNION
			
			SELECT PAG04_TRANSFERENCIAS.PAG04_ID, CB04_EMPRESA.CB04_COD_CONTA_VIRTUAL AS receiver_id, PAG04_TRANSFERENCIAS.PAG04_VLR * 100 AS amount_cents
			FROM PAG04_TRANSFERENCIAS
			JOIN user ON user.id = PAG04_TRANSFERENCIAS.PAG04_ID_USER_CONTA_ORIGEM
			JOIN CB04_EMPRESA  ON CB04_EMPRESA.CB04_ID = user.id_company
			WHERE PAG04_TRANSFERENCIAS.PAG04_DT_DEP IS NULL AND PAG04_TRANSFERENCIAS.PAG04_TIPO = 'E2B'
        
        ";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);      

        return $command->query()->readAll();
    }
 
    
    private function createTransacao($type, $origem, $destino, $valor, $dtPrevisao, $pedido, $dtDeposito = null)
    {   
        $trans = new self();
        $trans->PAG04_TIPO = $type;
        $trans->PAG04_ID_USER_CONTA_ORIGEM = $origem;
        $trans->PAG04_ID_USER_CONTA_DESTINO = $destino;
        $trans->PAG04_VLR = $valor;
        $trans->PAG04_DT_PREV = $dtPrevisao;
        $trans->PAG04_ID_PEDIDO = $pedido;
        $trans->PAG04_DT_DEP = $dtDeposito;
        $trans->save();
    }
 
    /*
     * Master to Empresa
     */
    public function createM2E($empresa, $valor, $dtPrevisao, $pedido = null)
    {
        return $this->createTransacao(self::M2E, \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_MA'), $empresa, $valor, $dtPrevisao, $pedido);
    }
 
    /*
     * Master to Cliente
     */
    public function createM2C($cliente, $valor, $pedido = null)
    {
        $now = date('Y-m-d H:i:s');
        return $this->createTransacao(self::M2C, \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_MA'), $cliente, $valor, $now, $pedido, $now);
    }
 
    /*
     * Empresa to Master
     */
    public function createE2M($empresa, $valor, $dtPrevisao, $pedido = null)
    {
        return $this->createTransacao(self::E2M, $empresa, \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_MA'), $valor, $dtPrevisao, $pedido);
    }
    
    /*
     * Empresa to Admin
     */
    public function createE2ADM($empresa, $valor, $dtPrevisao, $pedido = null)
    {
        return $this->createTransacao(self::E2ADM, $empresa, \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CT_SA'), $valor, $dtPrevisao, $pedido);
    }
    
    /*
     * Empresa to Adquirente
     */
    public function createE2ADQ($empresa, $valor, $dtPrevisao, $pedido = null)
    {
        return $this->createTransacao(self::E2ADQ, $empresa, \common\models\SYS01PARAMETROSGLOBAIS::getValor('U_CTADQ'), $valor, $dtPrevisao, $pedido);
    }
    
    /*
     * Cliente to Empresa
     */
    public function createC2E($cliente, $empresa, $valor, $pedido = null)
    {
        $now = date('Y-m-d H:i:s');
        return $this->createTransacao(self::C2E, $cliente, $empresa, $valor, $now, $pedido, $now);
    }
    
}
