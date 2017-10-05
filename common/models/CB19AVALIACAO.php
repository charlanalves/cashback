<?php

namespace common\models;

use Yii;
use common\models\base\CB19AVALIACAO as BaseCB19AVALIACAO;

/**
 * This is the model class for table "CB19_AVALIACAO".
 */
class CB19AVALIACAO extends BaseCB19AVALIACAO {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB19_EMPRESA_ID', 'CB19_NOME'], 'required'],
            [['CB19_EMPRESA_ID', 'CB19_STATUS'], 'integer'],
            [['CB19_NOME'], 'string', 'max' => 150]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function avaliacaoEmpresa($empresa) {

        $sql = "SELECT *
                FROM CB19_AVALIACAO
                WHERE CB19_EMPRESA_ID = :empresa
                ORDER BY CB19_STATUS DESC, CB19_NOME";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':empresa', $empresa);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function getAvaliacao($id) {

        $sql = "SELECT CB20_ID, CB23_DESCRICAO,CB23_ICONE
                FROM CB19_AVALIACAO
                INNER JOIN CB20_ITEM_AVALIACAO ON( CB20_AVALIACAO_ID = CB19_ID)
                INNER JOIN CB23_TIPO_AVALIACAO ON( CB20_TIPO_AVALICAO_ID = CB23_ID)
                WHERE CB19_ID = :id AND CB20_STATUS = 1
                ORDER BY CB23_DESCRICAO";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':id', $id);
        return $command->query()->readAll();
    }

}
