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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_DT_PREV', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO', 'PAG04_VLR', 'PAG04_TIPO'], 'required'],
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
 
	
}
