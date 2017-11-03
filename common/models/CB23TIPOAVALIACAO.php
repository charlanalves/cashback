<?php

namespace common\models;

use Yii;
use common\models\base\CB23TIPOAVALIACAO as BaseCB23TIPOAVALIACAO;

/**
 * This is the model class for table "CB23_TIPO_AVALIACAO".
 */
class CB23TIPOAVALIACAO extends BaseCB23TIPOAVALIACAO {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB23_CATEGORIA_ID', 'CB23_DESCRICAO', 'CB23_ICONE'], 'required'],
            [['CB23_CATEGORIA_ID', 'CB23_STATUS'], 'integer'],
            [['CB23_DESCRICAO'], 'string', 'max' => 100],
            [['CB23_ICONE'], 'string', 'max' => 50]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function gridQueryItensAvaliacaoMain($param) {
        $sql = "SELECT CB23_ID AS ID, CB23_ID, CB23_DESCRICAO, CB23_ICONE,
                CONCAT('img/excluir.png^Excluir item^javascript:excluirItemAvaliacao(', CB23_ID, ');') AS EXCLUIR
                FROM CB23_TIPO_AVALIACAO
                WHERE CB23_STATUS = 1 AND CB23_CATEGORIA_ID = :cat
                ORDER BY CB23_DESCRICAO";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cat', $param['cat']);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsItensAvaliacaoMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB23_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'CB23_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB23_DESCRICAO'], 'align' => 'left', 'width' => '150', 'type' => 'ro', 'id' => 'CB23_DESCRICAO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB23_ICONE'], 'align' => 'left', 'width' => '120', 'type' => 'ro', 'id' => 'CB23_ICONE'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'Excluir', 'align' => 'center', 'width' => '70', 'type' => 'img', 'id' => 'EXCLUIR'], 'filter' => ['title' => '']],
        ];
    }



}
