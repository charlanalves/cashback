<?php

namespace common\models;

use Yii;
use common\models\base\CB11ITEMCATEGORIA as BaseCB11ITEMCATEGORIA;

/**
 * This is the model class for table "CB11_ITEM_CATEGORIA".
 */
class CB11ITEMCATEGORIA extends BaseCB11ITEMCATEGORIA {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB11_CATEGORIA_ID', 'CB11_DESCRICAO'], 'required'],
            [['CB11_CATEGORIA_ID', 'CB11_STATUS'], 'integer'],
            [['CB11_DESCRICAO'], 'string', 'max' => 30],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function gridQueryItensCategoriaMain($param) {
        $sql = "SELECT CB11_ID AS ID, CB11_ID, CB11_DESCRICAO,
                CONCAT('img/excluir.png^Excluir item^javascript:excluirItemCategoria(', CB11_ID, ');') AS EXCLUIR
                FROM CB11_ITEM_CATEGORIA
                WHERE CB11_STATUS = 1 AND CB11_CATEGORIA_ID = :cat
                ORDER BY CB11_DESCRICAO";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cat', $param['cat']);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsItensCategoriaMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB11_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'CB11_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB11_DESCRICAO'], 'align' => 'left', 'width' => '250', 'type' => 'ro', 'id' => 'CB11_DESCRICAO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'Excluir', 'align' => 'center', 'width' => '70', 'type' => 'img', 'id' => 'EXCLUIR'], 'filter' => ['title' => '']],
        ];
    }

}
