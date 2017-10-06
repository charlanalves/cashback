<?php

namespace common\models;

use common\models\base\CB22COMENTARIOAVALIACAO as BaseCB22COMENTARIOAVALIACAO;

/**
 * This is the model class for table "CB22_COMENTARIO_AVALIACAO".
 */
class CB22COMENTARIOAVALIACAO extends BaseCB22COMENTARIOAVALIACAO {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB22_AVALIACAO_ID', 'CB22_PRODUTO_PEDIDO_ID', 'CB22_COMENTARIO'], 'required'],
            [['CB22_AVALIACAO_ID', 'CB22_PRODUTO_PEDIDO_ID'], 'integer'],
            [['CB22_COMENTARIO'], 'string', 'max' => 250]
        ]);
    }

    /**
     * getComentariosByEmpresa
     * Obtem percentual de aprovação do item da avaliacao de uma empresa
     */
    public function getComentariosByEmpresa($empresa) {

        $sql = "SELECT DATE_FORMAT(CB22_DATA,'%d/%m/%Y %H:%i') as CB16_DT, CB22_COMENTARIO , CB17_NOME_PRODUTO
                FROM CB22_COMENTARIO_AVALIACAO
                INNER JOIN CB19_AVALIACAO ON (CB19_ID = CB22_AVALIACAO_ID)
                INNER JOIN CB17_PRODUTO_PEDIDO ON (CB17_ID = CB22_PRODUTO_PEDIDO_ID)
                WHERE CB19_EMPRESA_ID = :empresa
                ORDER BY CB22_DATA DESC
                LIMIT 1000";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':empresa', $empresa);
        return $command->query()->readAll();
    }

}
