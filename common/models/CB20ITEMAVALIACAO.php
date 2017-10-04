<?php

namespace common\models;

use Yii;
use common\models\base\CB20ITEMAVALIACAO as BaseCB20ITEMAVALIACAO;

/**
 * This is the model class for table "CB20_ITEM_AVALIACAO".
 */
class CB20ITEMAVALIACAO extends BaseCB20ITEMAVALIACAO {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB20_AVALIACAO_ID', 'CB20_TIPO_AVALICAO_ID'], 'required'],
            [['CB20_AVALIACAO_ID', 'CB20_TIPO_AVALICAO_ID', 'CB20_STATUS'], 'integer']
        ]);
    }
    
    /**
     * getItensAvaliacao
     * Obtem os itens ativos da avaliacao
     */
    public function getItensAvaliacao($avaliacao) {

        $sql = "SELECT CB20_ITEM_AVALIACAO.*, CB23_DESCRICAO
                FROM CB20_ITEM_AVALIACAO
                INNER JOIN CB23_TIPO_AVALIACAO ON (CB23_CATEGORIA_ID = CB20_TIPO_AVALICAO_ID AND CB23_STATUS = 1)
                WHERE CB20_AVALIACAO_ID = :avaliacao AND  CB20_STATUS = 1
                ORDER BY CB23_DESCRICAO";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':avaliacao', $avaliacao);
        return $command->query()->readAll();
    }

}
