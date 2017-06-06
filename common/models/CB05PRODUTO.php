<?php

namespace common\models;

use Yii;
use common\models\base\CB05PRODUTO as BaseCB05PRODUTO;

/**
 * This is the model class for table "CB05_PRODUTO".
 */
class CB05PRODUTO extends BaseCB05PRODUTO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB05_EMPRESA_ID', 'CB05_NOME_CURTO', 'CB05_TITULO'], 'required'],
            [['CB05_EMPRESA_ID', 'CB05_ATIVO'], 'integer'],
            [['CB05_DESCRICAO', 'CB05_IMPORTANTE'], 'string'],
            [['CB05_NOME_CURTO'], 'string', 'max' => 15],
            [['CB05_TITULO'], 'string', 'max' => 30],
            
            
        ]);
    }
	
}
