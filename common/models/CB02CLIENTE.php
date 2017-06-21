<?php

namespace common\models;

use Yii;
use common\models\base\CB02CLIENTE as BaseCB02CLIENTE;

/**
 * This is the model class for table "CB02_CLIENTE".
 */
class CB02CLIENTE extends BaseCB02CLIENTE
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB02_ID_USUARIO', 'CB02_TEL_DDD', 'CB02_TEL_NUMERO', 'CB02_NUMERO', 'CB02_STATUS'], 'integer'],
            [['CB02_NOME', 'CB02_CPF_CNPJ', 'CB02_EMAIL'], 'required'],
            [['CB02_DADOS_API_TOKEN', 'CB02_COD_CONTA_VIRTUAL'], 'string'],
            [['CB02_DT_CADASTRO', 'CB02_DATA_NASCIMENTO'], 'safe'],
            [['CB02_NOME', 'CB02_EMAIL'], 'string', 'max' => 50],
            [['CB02_CPF_CNPJ'], 'string', 'max' => 14],
            [['CB02_LOGRADOURO'], 'string', 'max' => 500],
            [['CB02_BAIRRO', 'CB02_CEP', 'CB02_CIDADE', 'CB02_UF', 'CB02_COMPLEMENTO'], 'string', 'max' => 100],
            [['CB02_PAIS'], 'string', 'max' => 2],
            
            
        ]);
    }
	
}
