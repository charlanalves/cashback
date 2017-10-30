<?php

namespace common\models;

use common\models\base\EstabelecimentoExtratoModel as BaseEstabelecimentoExtratoModel;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class EstabelecimentoExtratoModel extends BaseEstabelecimentoExtratoModel {

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'PEDIDO_ID' => 'PEDIDO',
            'DATA_OPERACAO' => 'DATA OPERAÇÃO',
            'VALOR_VENDA' => 'VALOR VENDA',
            'TAXA_ADQUIRENTE' => 'TAXA ADQUIRENTE',
            'TAXA_ESTALECA' => 'TAXA ESTALECA',
            'DINHEIRO_VOLTA' => 'DINHEIRO DE VOLTA',
            'SALDO' => 'SALDO',
            'DATA_LIBERACAO' => 'DATA LIBERAÇÃO'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function saldoAtual() {
        $id_company = \Yii::$app->user->identity->id_company;
        $sql = "SELECT SUM(SALDO) AS SALDO 
                FROM VIEW_EXTRATO_ESTABELECIMENTO 
                INNER JOIN CB16_PEDIDO ON (CB16_ID = PEDIDO_ID AND CB16_EMPRESA_ID = :empresa)
                WHERE DATA_LIBERACAO IS NOT NULL";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':empresa', $id_company);
        return $command->queryOne()['SALDO'];
    }

    /**
     * @inheritdoc
     */
    public static function saldoPendente() {
        $id_company = \Yii::$app->user->identity->id_company;
        $sql = "SELECT SUM(SALDO) AS SALDO 
                FROM VIEW_EXTRATO_ESTABELECIMENTO 
                INNER JOIN CB16_PEDIDO ON (CB16_ID = PEDIDO_ID AND CB16_EMPRESA_ID = :empresa)
                WHERE DATA_LIBERACAO IS NULL";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':empresa', $id_company);
        return $command->queryOne()['SALDO'];
    }

    /**
     * @inheritdoc
     */
    public function gridQueryExtratoMain() {
        $id_company = \Yii::$app->user->identity->id_company;
        $query = "
            SELECT PEDIDO_ID,
                IFNULL(DATA_OPERACAO, '-') AS DATA_OPERACAO,
                IFNULL(DATA_LIBERACAO, '-') AS DATA_LIBERACAO,
                IFNULL(Replace(Replace(Replace(Format(VALOR_VENDA, 2), '.', '|'), ',', '.'), '|', ','), '-') AS VALOR_VENDA,
                IFNULL(Replace(Replace(Replace(Format(TAXA_ADQUIRENTE, 2), '.', '|'), ',', '.'), '|', ','), '-') AS TAXA_ADQUIRENTE,
                IFNULL(Replace(Replace(Replace(Format(TAXA_ESTALECA, 2), '.', '|'), ',', '.'), '|', ','), '-') AS TAXA_ESTALECA,
                IFNULL(Replace(Replace(Replace(Format(DINHEIRO_VOLTA, 2), '.', '|'), ',', '.'), '|', ','), '-') AS DINHEIRO_VOLTA,
                IFNULL(Replace(Replace(Replace(Format(SALDO, 2), '.', '|'), ',', '.'), '|', ','), '-') AS SALDO
            FROM VIEW_EXTRATO_ESTABELECIMENTO
            INNER JOIN CB16_PEDIDO ON (CB16_ID = PEDIDO_ID AND CB16_EMPRESA_ID = :empresa)";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $command->bindValue(':empresa', $id_company);
        $reader = $command->query();

        return $reader->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsExtratoMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['PEDIDO_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'PEDIDO_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['DATA_OPERACAO'], 'align' => 'center', 'width' => '150', 'type' => 'ro', 'id' => 'DATA_OPERACAO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['VALOR_VENDA'], 'align' => 'right', 'width' => '120', 'type' => 'ro', 'id' => 'VALOR_VENDA'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['TAXA_ADQUIRENTE'], 'align' => 'right', 'width' => '120', 'type' => 'ro', 'id' => 'TAXA_ADQUIRENTE'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['TAXA_ESTALECA'], 'align' => 'right', 'width' => '120', 'type' => 'ro', 'id' => 'TAXA_ESTALECA'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['DINHEIRO_VOLTA'], 'align' => 'right', 'width' => '120', 'type' => 'ro', 'id' => 'DINHEIRO_VOLTA'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['SALDO'], 'align' => 'right', 'width' => '120', 'type' => 'ro', 'id' => 'SALDO'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['DATA_LIBERACAO'], 'align' => 'center', 'width' => '150', 'type' => 'ro', 'id' => 'DATA_LIBERACAO'], 'filter' => ['title' => '#text_filter']],
        ];
    }

}
