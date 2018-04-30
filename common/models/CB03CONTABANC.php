<?php

namespace common\models;

use Yii;
use common\models\base\CB03CONTABANC as BaseCB03CONTABANC;

/**
 * This is the model class for table "CB03_CONTA_BANC".
 */
class CB03CONTABANC extends BaseCB03CONTABANC
{

    const SCENARIO_SAQUE = 'saque';
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB03_NOME_BANCO', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_AGENCIA', 'CB03_USER_ID', 'CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'required', 'message'=> 'O campo <strong>{attribute}</strong> não pode estar vazio.'],
            [['CB03_TP_CONTA', 'CB03_STATUS', 'CB03_USER_ID'], 'integer'],
            [['CB03_SAQUE_MIN', 'CB03_SAQUE_MAX'], 'number'],
            [['CB03_COD_BANCO'], 'string', 'max' => 10],
            [['CB03_NOME_BANCO'], 'string', 'max' => 50],      
            [['CB03_USER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['CB03_USER_ID' => 'id']],
            [['CB03_VALOR'], 'compare', 'operator' => '>=', 'compareAttribute' => 'CB03_SAQUE_MIN', 'type' => 'number', 'message' => 'Valor mínimo para saque: R$ {compareValue}'],
            [['CB03_VALOR'], 'compare', 'operator' => '<=', 'compareAttribute' => 'CB03_SAQUE_MAX', 'type' => 'number', 'message' => 'O valor informado é maior que seu saldo de R$ {compareValue}'],
            [['CB03_COD_BANCO'], 'setMaskBancaria'],
            
            
        ];
    }
    
    public function setMaskBancaria($a, $b)
    {
        // formata dados da conta e agencia
        $dMask = \Yii::$app->u->setMaskBancaria(
                $this->CB03_COD_BANCO, 
                $this->CB03_AGENCIA, 
                $this->CB03_NUM_CONTA, 
                $this->CB03_TP_CONTA
        );
        
        $this->CB03_AGENCIA = $dMask['A'];
        $this->CB03_NUM_CONTA = $dMask['C'];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SAQUE] = ['CB03_USER_ID', 'CB03_NOME_BANCO', 'CB03_COD_BANCO', 'CB03_AGENCIA', 'CB03_TP_CONTA', 'CB03_NUM_CONTA', 'CB03_VALOR'];
        return $scenarios;        
    }

    private static function getContaBancariaUser($idCompany, $perfil)
    {
        $sql = "SELECT * FROM " . self::tableName() . " 
                WHERE CB03_USER_ID = (
                	SELECT u.ID
                	FROM user u 
                	INNER JOIN auth_assignment a ON (a.user_id = u.id AND a.item_name = :perfilUser) 
                	WHERE u.ID_COMPANY = :idCompany
                	ORDER BY u.ID
                	LIMIT 1)
                ORDER BY CB03_ID DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindParam(':idCompany', $idCompany);
        $command->bindParam(':perfilUser', $perfil);
        return $command->queryOne();
    }

    public static function getContaBancariaEmpresa($idEmpresa)
    {
        return self::getContaBancariaUser($idEmpresa, User::PERFIL_ESTABELECIMENTO);
    }

    public static function getContaBancariaRepresentante($idRepresentante)
    {
        return self::getContaBancariaUser($idRepresentante, User::PERFIL_REPRESENTANTE);
    }

    public static function getContaBancariaFuncionario($idFuncionario)
    {
        return self::getContaBancariaUser($idFuncionario, User::PERFIL_FUNCIONARIO);
    }

	
}
