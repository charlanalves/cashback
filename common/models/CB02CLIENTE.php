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
            [['CB02_NOME', 'CB02_CPF', 'CB02_EMAIL', 'CB02_DADOS_API_TOKEN'], 'required'],
            [['CB02_DADOS_API_TOKEN'], 'string'],
            [['CB02_STATUS'], 'integer'],
            [['CB02_DT_CADASTRO'], 'safe'],
            [['CB02_NOME', 'CB02_EMAIL'], 'string', 'max' => 50],
            [['CB02_CPF'], 'string', 'max' => 14],
            
            
        ]);
    }
	
}
