<?php

namespace common\models;

use Yii;
use common\models\base\CB21RESPOSTAAVALIACAO as BaseCB21RESPOSTAAVALIACAO;

/**
 * This is the model class for table "CB21_RESPOSTA_AVALIACAO".
 */
class CB21RESPOSTAAVALIACAO extends BaseCB21RESPOSTAAVALIACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB21_ITEM_AVALIACAO_ID', 'CB21_PRODUTO_PEDIDO_ID', 'CB21_NOTA'], 'required'],
            [['CB21_ITEM_AVALIACAO_ID', 'CB21_PRODUTO_PEDIDO_ID', 'CB21_NOTA'], 'integer']
        ]);
    }
    
    /**
     * getNotaPercentualItemByEmpresa
     * Obtem percentual de aprovação do item da avaliacao de uma empresa
     */
    public function getNotaPercentualItemByEmpresa($empresa) {

        $sql = "SELECT CB23_DESCRICAO, COUNT(CB21_PRODUTO_PEDIDO_ID) AS QTD, ((SUM(CB21_NOTA) / COUNT(CB21_PRODUTO_PEDIDO_ID)) / 5) * 100 AS PERCENTUAL
                FROM CB21_RESPOSTA_AVALIACAO
                INNER JOIN CB20_ITEM_AVALIACAO ON (CB20_ID = CB21_ITEM_AVALIACAO_ID)
                INNER JOIN CB23_TIPO_AVALIACAO ON (CB23_ID = CB20_TIPO_AVALICAO_ID)
                INNER JOIN CB17_PRODUTO_PEDIDO ON (CB17_ID = CB21_PRODUTO_PEDIDO_ID)
                INNER JOIN CB16_PEDIDO ON(CB16_ID = CB17_PEDIDO_ID)
                WHERE CB16_EMPRESA_ID = :empresa
                GROUP BY CB23_DESCRICAO
                ORDER BY PERCENTUAL DESC";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':empresa', $empresa);
        return $command->query()->readAll();
    }

}
