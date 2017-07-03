<?php

namespace common\models;

use Yii;
use common\models\base\CB10CATEGORIA as BaseCB10CATEGORIA;

/**
 * This is the model class for table "CB10_CATEGORIA".
 */
class CB10CATEGORIA extends BaseCB10CATEGORIA {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB10_NOME'], 'required'],
            [['CB10_STATUS'], 'integer'],
            [['CB10_NOME'], 'string', 'max' => 30],
            [['CB10_ICO'], 'string', 'max' => 200],
        ]);
    }

    public static function getMaxCachback()
    {
        $sql = "SELECT CB10_ID, CB10_NOME, MAX_CB_CATEGORIA, CB10_ICO
                FROM CB10_CATEGORIA
                LEFT JOIN ( 
                    SELECT CB04_CATEGORIA_ID, MAX(CB06_DINHEIRO_VOLTA) AS MAX_CB_CATEGORIA
                    FROM CB04_EMPRESA 
                    INNER JOIN CB05_PRODUTO ON (CB05_EMPRESA_ID = CB04_ID AND CB05_ATIVO = 1)
                    INNER JOIN CB06_VARIACAO ON (CB06_PRODUTO_ID = CB05_ID)
                    GROUP BY CB04_CATEGORIA_ID
                ) CB ON (CB.CB04_CATEGORIA_ID = CB10_ID)
                WHERE CB10_ID NOT IN (3,4)
                ORDER BY CB10_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        return $command->query()->readAll();
    }
    
    
}
