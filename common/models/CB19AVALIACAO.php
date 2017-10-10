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
    public static function getPedidoAvaliacao($user) {

        $sql = "SELECT CB06_AVALIACAO_ID, CB16_ID, CB17_ID, CB17_NOME_PRODUTO, CB04_NOME
                FROM CB16_PEDIDO
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB17_PEDIDO_ID = CB16_ID)
                INNER JOIN CB04_EMPRESA ON(CB04_ID = CB16_EMPRESA_ID)
                INNER JOIN CB06_VARIACAO ON(CB06_ID = CB17_VARIACAO_ID)
                INNER JOIN CB10_CATEGORIA ON(CB10_ID = CB04_CATEGORIA_ID)
                WHERE 
                    CB16_USER_ID = :user AND 
                    CB17_AVALIADO IS NULL AND 
                    CB06_AVALIACAO_ID IS NOT NULL AND 
                    NOW() >= DATE_ADD(CB16_DT_APROVACAO, INTERVAL CB10_TIME_AVALIACAO HOUR) AND 
                    /* DELIVERY PAGO E ENTREGUE OU PEDIDO BAIXADO */
                    ((CB16_STATUS_DELIVERY = 3 AND CB16_STATUS = 30) OR CB16_STATUS = 20)";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':user', $user);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public static function getAvaliacao($id) {

        $sql = "SELECT CB19_ID, CB20_ID, CB23_DESCRICAO,CB23_ICONE
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
