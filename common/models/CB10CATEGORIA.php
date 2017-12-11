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

    public static function getMaxCachback() {
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
                AND CB10_CATEGORIA.CB10_STATUS = 1
                ORDER BY CB10_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridQueryCategoriaMain() {
        $sql = "SELECT CB10_ID AS ID, CB10_ID, CB10_NOME, CB10_ICO,
                CONCAT('img/editar.png^Itens da categoria^javascript:modalItensCategoria(', CB10_ID, ');') AS ITENS_CATEGORIA,
                CONCAT('img/editar.png^Itens da avaliação^javascript:modalItensAvaliacao(', CB10_ID, ');') AS ITENS_AVALIACAO,
                CONCAT('img/excluir.png^Excluir categoria^javascript:excluirCategoria(', CB10_ID, ');') AS EXCLUIR
                FROM CB10_CATEGORIA
                WHERE CB10_STATUS = 1
                ORDER BY CB10_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsCategoriaMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB10_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'CB10_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB10_NOME'], 'align' => 'left', 'width' => '250', 'type' => 'ro', 'id' => 'CB10_NOME'], 'filter' => ['title' => '#text_filter']],
//            ['sets' => ['title' => $al['CB10_ICO'], 'align' => 'left', 'width' => '120', 'type' => 'ro', 'id' => 'CB10_ICO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'Itens da categoria', 'align' => 'center', 'width' => '80', 'type' => 'img', 'id' => 'ITENS_CATEGORIA'], 'filter' => ['title' => '']],
            ['sets' => ['title' => 'Itens da avaliação', 'align' => 'center', 'width' => '80', 'type' => 'img', 'id' => 'ITENS_AVALIACAO'], 'filter' => ['title' => '']],
            ['sets' => ['title' => 'Excluir', 'align' => 'center', 'width' => '80', 'type' => 'img', 'id' => 'EXCLUIR'], 'filter' => ['title' => '']],
        ];
    }

}
