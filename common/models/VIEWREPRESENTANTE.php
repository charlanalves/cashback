<?php

namespace common\models;
use Yii;


/**
 * This is the model class for table "VIEW_REPRESENTANTE".
 */
class VIEWREPRESENTANTE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB04_ID', 'CB04_CONTA_VERIFICADA', 'CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_FLG_DELIVERY', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO', 'CB04_TIPO'], 'integer'],
            [['CB04_DADOS_API_TOKEN', 'CB04_COD_CONTA_VIRTUAL', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO'], 'string'],
            [['CB04_COD_CONTA_VIRTUAL', 'CB04_NOME', 'CB04_CNPJ', 'CB04_TEL_NUMERO', 'CB04_CATEGORIA_ID', 'CB04_EMAIL', 'CB04_FUNCIONAMENTO', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO', 'CB04_END_CEP'], 'required'],
            [['CB04_NOME', 'CB04_TEL_NUMERO', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_CNPJ'], 'string', 'max' => 14],
            [['CB04_EMAIL'], 'string', 'max' => 200],
            [['CB04_URL_LOGOMARCA'], 'string', 'max' => 100],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_CEP'], 'string', 'max' => 8],
            [['CB04_END_LATITUDE', 'CB04_END_LONGITUDE'], 'string', 'max' => 20],           
        ]);
    }
	
}
