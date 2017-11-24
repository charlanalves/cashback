<?php

namespace common\models;

use Yii;
use common\models\base\CB07CASHBACK as BaseCB07CASHBACK;

/**
 * This is the model class for table "CB07_CASH_BACK".
 */
class CB07CASHBACK extends BaseCB07CASHBACK
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
	    [
            [['CB07_PRODUTO_ID', 'CB07_VARIACAO_ID', 'CB07_DIA_SEMANA'], 'integer'],
            [['CB07_DIA_SEMANA', 'CB07_PERCENTUAL', 'CB07_EMPRESA_ID'], 'required'],            
            [['CB07_PERCENTUAL'], 'number', 'max'=>100, 'message' => "O valor máximo é de 100%."],
        ];
    }
    
    public static function getCashback($produto)
    {
        $query = "
            SELECT CB05_ID AS PRODUTO_ID,CB06_ID AS VARIACAO_ID,CB05_TITULO AS PRODUTO,CB06_DESCRICAO AS VARIACAO,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 1, CB07_PERCENTUAL, NULL)) AS DIA_SEG,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 2, CB07_PERCENTUAL, NULL)) AS DIA_TER,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 3, CB07_PERCENTUAL, NULL)) AS DIA_QUA,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 4, CB07_PERCENTUAL, NULL)) AS DIA_QUI,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 5, CB07_PERCENTUAL, NULL)) AS DIA_SEX,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 6, CB07_PERCENTUAL, NULL)) AS DIA_SAB,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 0, CB07_PERCENTUAL, NULL)) AS DIA_DOM
            FROM CB07_CASH_BACK 
            LEFT JOIN CB05_PRODUTO on (CB05_ID = CB07_PRODUTO_ID AND CB05_ID = :produto)
            LEFT JOIN CB06_VARIACAO on (CB06_ID = CB07_VARIACAO_ID AND CB06_PRODUTO_ID = :produto)
            WHERE CB05_ID IS NOT NULL OR CB06_ID IS NOT NULL
            GROUP BY CB05_ID,CB06_ID";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $command->bindParam(':produto', $produto);
        $reader = $command->query();
        
        return $reader->readAll();
    }
    
    public static function getCashbackDiario($idEmpresa)
    {
        $query = "
            SELECT
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 1, CB07_PERCENTUAL, NULL)) AS DIA_1,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 2, CB07_PERCENTUAL, NULL)) AS DIA_2,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 3, CB07_PERCENTUAL, NULL)) AS DIA_3,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 4, CB07_PERCENTUAL, NULL)) AS DIA_4,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 5, CB07_PERCENTUAL, NULL)) AS DIA_5,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 6, CB07_PERCENTUAL, NULL)) AS DIA_6,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 0, CB07_PERCENTUAL, NULL)) AS DIA_0          
            FROM CB07_CASH_BACK 
            WHERE CB07_EMPRESA_ID = :idEmpresa
            ";
           
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $command->bindParam(':idEmpresa', $idEmpresa);
        $reader = $command->query();
        
        return $reader->readAll();
    }
    public static function getFormasPgtoEmpresa($idEmpresa, $tipoFormaPgto)
    {
        $query = "
            SELECT 
            CB04_EMPRESA.CB04_NOME,
            CB08_FORMA_PAGAMENTO.CB08_NOME, 
            CB08_FORMA_PAGAMENTO.CB08_URL_IMG
            FROM CB04_EMPRESA
            JOIN CB09_FORMA_PAGTO_EMPRESA ON CB09_FORMA_PAGTO_EMPRESA.CB09_ID_EMPRESA = CB04_EMPRESA.CB04_ID
            JOIN CB08_FORMA_PAGAMENTO ON CB08_FORMA_PAGAMENTO.CB08_ID = CB09_FORMA_PAGTO_EMPRESA.CB09_ID_FORMA_PAG
            WHERE CB04_EMPRESA.CB04_ID =:idEmpresa AND CB08_FORMA_PAGAMENTO.CB08_TIPO =:tipoFormaPgto
            GROUP BY CB04_EMPRESA.CB04_NOME, CB08_FORMA_PAGAMENTO.CB08_NOME
            ";
           
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $command->bindParam(':idEmpresa', $idEmpresa);
        $command->bindParam(':tipoFormaPgto', $tipoFormaPgto);
        $reader = $command->query();
        
        return $reader->readAll();
    }
    
    
    
    public function saveCashback($data) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            
            // verifica se é produto ou variacao
            if (substr($data['PRODUTO_VARIACAO'], 0, 1) == 'P') {
                $data_['CB07_PRODUTO_ID'] = substr($data['PRODUTO_VARIACAO'], 1);
            } else {
                $data_['CB07_VARIACAO_ID'] = $data['PRODUTO_VARIACAO'];
            }
            $this->deleteCashback($data_);
            for ($i = 0; $i <= 6; $i++) {
                $data_['CB07_DIA_SEMANA'] = $i;
                $data_['CB07_PERCENTUAL'] = $data['DIA_' . $i];
                $CB07CASHBACK = new CB07CASHBACK();
                $CB07CASHBACK->setAttributes($data_);
                $CB07CASHBACK->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    public function saveCashbackDiario($data) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();       
        try {
            if (empty($idCompany = \Yii::$app->user->identity->id_company)) {
                throw new \Exception('Ocorreu um erro interno. O codigo da empresa não foi encontrado. Entre em contato com o suporte técnico.');
            }
            $this->deleteCashback(['CB07_EMPRESA_ID' => $idCompany]);
            
            for ($i = 0; $i <= 6; $i++) {
                $data['CB07_DIA_SEMANA'] = $i;
                $data['CB07_PERCENTUAL'] = $data['DIA_' . $i];
                $data['CB07_EMPRESA_ID'] = $idCompany;
                $CB07CASHBACK = new CB07CASHBACK();
                $CB07CASHBACK->setAttributes($data);
                $CB07CASHBACK->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    public function deleteCashback($data) {
        self::deleteAll($data);
    }
    
    public static function getCurrentCashback($empresa) {
        $current = self::find()->where("CB07_EMPRESA_ID=" . $empresa . " AND CB07_DIA_SEMANA=" . date('w'))->one();
        return $current ? $current->CB07_PERCENTUAL : 0;
    }
	
}
