<?php

namespace common\models;

use Yii;
use common\models\base\VIEWEXTRATOCLIENTE as BaseVIEWEXTRATOCLIENTE;

/**
 * This is the model class for table "VIEW_EXTRATO_CLIENTE".
 */
class VIEWEXTRATOCLIENTE extends BaseVIEWEXTRATOCLIENTE
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['DT1'], 'safe'],
            [['VLR1', 'VLR2'], 'number'],
            [['STATUS', 'CLIENTE'], 'integer'],
            [['TRANSACAO_ID', 'TRANSFERENCIA_ID', 'EMPRESA_ID'], 'string', 'max' => 11],
            [['TIPO'], 'string', 'max' => 13],
            [['EMPRESA_NM'], 'string', 'max' => 50],
            [['DT2'], 'string', 'max' => 19],
        ]);
    }
    
  public static function saldoAtualByCliente($cliente)
    {
        $sql = "SELECT SUM(VLR2) AS SALDO FROM VIEW_EXTRATO_CLIENTE WHERE CLIENTE = :cliente";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    
    public static function saldoAtualByAuthKey($AuthKey)
    {
        return self::saldoAtualByCliente(User::getIdByAuthKey($AuthKey));
    }
	
}
