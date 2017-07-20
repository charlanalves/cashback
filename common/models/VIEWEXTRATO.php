<?php

namespace common\models;

use Yii;
use common\models\base\VIEWEXTRATO as BaseVIEWEXTRATO;

/**
 * This is the model class for table "VIEW_EXTRATO".
 */
class VIEWEXTRATO extends BaseVIEWEXTRATO
{
    
    public $tipos_para_estabelecimento = [
        "M2E" => "VENDA", 
        "E2ADQ" => "TARIFA ADQ", 
        "E2C" => "EMPRESA TO CLIENTE", 
        "E2ADM" => "TARIFA ESTALECAS", 
        "V2B" => "VIRTUAL TO BANCARIA", 
        "B2V" => "BANCARIA TO VIRTUAL",
        "E2M" => "CASHBACK"
    ];
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['TRANSFERENCIA_ID', 'PEDIDO_ID', 'USER'], 'integer'],
            [['DT_CRIACAO', 'DT_PREVISAO', 'DT_DEPOSITO'], 'safe'],
            [['VALOR'], 'number'],
            [['TIPO'], 'string', 'max' => 5],
        ]);
    }
	
    public static function saldoAtualByCliente($cliente) 
    {
        $sql = "SELECT SUM(VALOR) AS SALDO FROM VIEW_EXTRATO WHERE USER = :cliente AND (DT_DEPOSITO IS NOT NULL OR TIPO = 'V2B')";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    public static function saldoReceberByCliente($cliente) 
    {
        $sql = "SELECT SUM(VALOR) AS SALDO FROM VIEW_EXTRATO WHERE USER = :cliente AND (DT_DEPOSITO IS NULL OR TIPO = 'V2B')";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    public static function saldoPendenteByCliente($cliente) 
    {
        $sql = "SELECT SUM(VALOR) AS SALDO FROM VIEW_EXTRATO WHERE USER = :cliente AND (DT_DEPOSITO IS NULL AND TIPO <> 'V2B')";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    public static function saldoAtualePendenteByCliente($cliente) 
    {
        $sql = "SELECT 
                SUM(CASE WHEN DT_DEPOSITO IS NOT NULL OR TIPO = 'V2B' THEN VALOR ELSE 0 END) AS SALDO_LIBERADO,
                SUM(CASE WHEN DT_DEPOSITO IS NULL AND TIPO <> 'V2B' THEN VALOR ELSE 0 END) AS SALDO_PENDENTE
                FROM VIEW_EXTRATO 
                WHERE USER = :cliente
                GROUP BY USER";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne();
    }

    public static function saldoAtualByAuthKey($AuthKey)
    {
        return self::saldoAtualByCliente(User::getIdByAuthKey($AuthKey));
    }
    
}
