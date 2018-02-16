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
        "E2M" => "CASHBACK",
        "C2E" => "CLIENTE TO EMPRESA",
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
        $sql = "SELECT SUM(TBL.SALDO) AS SALDO FROM (
                    SELECT VIEW_EXTRATO.VALOR AS SALDO 
                        FROM VIEW_EXTRATO 
                        -- PEDIDOS DEPOSITADOS
                        INNER JOIN (
                                SELECT PEDIDO_ID 
                                FROM VIEW_EXTRATO 
                                WHERE TIPO = 'C2E' AND DT_DEPOSITO IS NOT NULL
                                ) E_C2E ON(E_C2E.PEDIDO_ID = VIEW_EXTRATO.PEDIDO_ID OR VIEW_EXTRATO.PEDIDO_ID IS NULL)
                        WHERE VIEW_EXTRATO.USER = :cliente /* AND VIEW_EXTRATO.TIPO IN ('C2E','E2ADQ','E2ADM','E2M') */
                        GROUP BY VIEW_EXTRATO.TRANSFERENCIA_ID,VIEW_EXTRATO.PEDIDO_ID,VIEW_EXTRATO.TIPO
                ) TBL";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    public static function saldoReceberByCliente($cliente) 
    {
        $sql = "SELECT SUM(SALDO) AS SALDO FROM (
                    SELECT VIEW_EXTRATO.VALOR AS SALDO 
                        FROM VIEW_EXTRATO 
                        -- PEDIDOS NAO DEPOSITADOS
                        INNER JOIN (
                                SELECT PEDIDO_ID 
                                FROM VIEW_EXTRATO 
                                WHERE TIPO = 'C2E' AND DT_DEPOSITO IS NULL
                                ) E_C2E ON(E_C2E.PEDIDO_ID = VIEW_EXTRATO.PEDIDO_ID OR VIEW_EXTRATO.PEDIDO_ID IS NULL)
                        WHERE VIEW_EXTRATO.USER = :cliente /*AND VIEW_EXTRATO.TIPO IN ('C2E','E2ADQ','E2ADM','E2M') */
                        GROUP BY VIEW_EXTRATO.TRANSFERENCIA_ID,VIEW_EXTRATO.PEDIDO_ID,VIEW_EXTRATO.TIPO
                ) TBL";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    public static function saldoPendenteByCliente($cliente) 
    {
        $sql = "SELECT SUM(VALOR) AS SALDO FROM VIEW_EXTRATO WHERE USER = :cliente AND DT_DEPOSITO IS NULL AND TIPO IN ('C2E','E2ADQ','E2ADM','E2M')";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    public static function saldoAtualePendenteByCliente($cliente) 
    {
        $sql = "SELECT 
                SUM(CASE WHEN DT_DEPOSITO IS NOT NULL AND TIPO IN ('C2E','E2ADQ','E2ADM','E2M', 'V2B') THEN VALOR ELSE 0 END) AS SALDO_LIBERADO,
                SUM(CASE WHEN DT_DEPOSITO IS NULL AND TIPO IN ('C2E','E2ADQ','E2ADM','E2M') THEN VALOR ELSE 0 END) AS SALDO_PENDENTE
                FROM VIEW_EXTRATO 
                WHERE USER = :cliente
                GROUP BY USER";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne();
    }
    
    public static function extractUser($idUser, $periodo) 
    {
        $sql = "SELECT VIEW_EXTRATO.*, A.* 
                FROM VIEW_EXTRATO
                INNER JOIN (
                    SELECT CB16_PEDIDO.*, CB04_EMPRESA.*
                    FROM CB16_PEDIDO 
                    INNER JOIN CB04_EMPRESA ON (CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID)
                    )A ON (VIEW_EXTRATO.PEDIDO_ID = A.CB16_ID OR VIEW_EXTRATO.PEDIDO_ID IS NULL)
                WHERE USER = :idUser AND DT_CRIACAO BETWEEN :periodo AND LAST_DAY(:periodo) AND TIPO IN('M2C','V2B')
                GROUP BY VIEW_EXTRATO.TRANSFERENCIA_ID
                ORDER BY DT_CRIACAO DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':idUser', $idUser);
        $command->bindValue(':periodo', $periodo . '-1');
        return $command->queryAll();
    }

    public static function saldoAtualByAuthKey($AuthKey)
    {
        return self::saldoAtualByCliente(User::getIdByAuthKey($AuthKey));
    }
    
    public static function comissaoVendasEmpresa($idUser, $empresa, $periodo) 
    {
        $sql = "SELECT VIEW_EXTRATO.*, A.* 
                FROM VIEW_EXTRATO
                INNER JOIN (
                    SELECT CB16_PEDIDO.*, CB04_EMPRESA.*
                    FROM CB16_PEDIDO 
                    INNER JOIN CB04_EMPRESA ON (CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID)
                    WHERE CB04_EMPRESA.CB04_ID = :empresa
                    )A ON (VIEW_EXTRATO.PEDIDO_ID = A.CB16_ID OR VIEW_EXTRATO.PEDIDO_ID IS NULL)
                WHERE USER = :idUser AND DT_CRIACAO BETWEEN :periodo AND LAST_DAY(:periodo) AND TIPO IN('M2F','M2R')
                GROUP BY VIEW_EXTRATO.TRANSFERENCIA_ID
                ORDER BY DT_CRIACAO DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':idUser', $idUser);
        $command->bindValue(':empresa', $empresa);
        // $periodo = ano/mes
        $command->bindValue(':periodo', $periodo . '-1');
        return $command->queryAll();
    }

}
