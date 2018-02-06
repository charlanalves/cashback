<?php

namespace common\models;

use Yii;
use common\models\base\VIEWFUNCIONARIO as BaseVIEWFUNCIONARIO;

/**
 * This is the model class for table "CB04_EMPRESA".
 * CB04_TIPO = 3
 */
class VIEWFUNCIONARIO extends BaseVIEWFUNCIONARIO
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(), [
            [['CB04_CNPJ', 'CB04_TEL_NUMERO', 'CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO', 'CB04_END_CEP', 'CB04_EMAIL', 'CB04_ID_EMPRESA'], 'required'],
            [['CB04_DADOS_API_TOKEN', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO', 'CB04_COD_CONTA_VIRTUAL'], 'string'],
            [['CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO', 'CB04_TIPO', 'CB04_ID_EMPRESA'], 'integer'],
            [['CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_URL_LOGOMARCA'], 'string', 'max' => 100],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_FLG_DELIVERY'], 'integer'],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_LONGITUDE', 'CB04_END_LATITUDE'], 'string', 'max' => 20],
            [['CB04_CNPJ'], 'string', 'max' => 11],
            ['CB04_END_CEP', 'filter', 'filter' => function ($value) {
                    return preg_replace("/[^0-9]/", "", $value);
                }],
            ['CB04_CNPJ', 'filter', 'filter' => function ($value) {
                    return preg_replace("/[^0-9]/", "", $value);
                }],
            [['CB04_END_CEP'], 'string', 'max' => 8],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function getFuncionarios()
    {
        return self::find()->where('CB04_TIPO = 3')->orderBy('CB04_ID DESC')->all();
    }

    /**
     * @inheritdoc
     */
    public static function getFuncionario($id)
    {
        return self::find()->where('CB04_ID=' . $id . ' AND CB04_STATUS = 1 AND CB04_TIPO = 3')->one();
    }
    
    /**
     * @inheritdoc
     */
    public function gridQueryFuncionariosMain($param)
    {
        $sql = "SELECT CB04_ID as ID, CB04_NOME, CB04_ID, CB04_STATUS,
                CONCAT('img/editar.png^Editar^javascript:gridFuncionario_editar(', CB04_ID, ');') AS EDITAR
                FROM " . self::tableName() . "
                WHERE CB04_ID_EMPRESA = :empresa 
                ORDER BY CB04_STATUS DESC,CB04_ID DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':empresa', $param['empresa']);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsFuncionariosMain()
    {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB04_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'CB04_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB04_NOME'], 'align' => 'left', 'width' => '*', 'type' => 'ro', 'id' => 'CB04_NOME'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'ATIVO', 'align' => 'center', 'width' => '80', 'type' => 'ch', 'id' => 'CB04_STATUS'], 'filter' => ['title' => '']],
            ['sets' => ['title' => 'EDITAR', 'align' => 'center', 'width' => '80', 'type' => 'img', 'id' => 'EDITAR'], 'filter' => ['title' => '']],
        ];
    }

}
