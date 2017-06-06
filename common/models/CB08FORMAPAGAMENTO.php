<?php

namespace common\models;

use Yii;
use common\models\base\CB08FORMAPAGAMENTO as BaseCB08FORMAPAGAMENTO;

/**
 * This is the model class for table "CB08_FORMA_PAGAMENTO".
 */
class CB08FORMAPAGAMENTO extends BaseCB08FORMAPAGAMENTO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB08_NOME', 'CB08_URL_IMG'], 'required'],
            [['CB08_STATUS'], 'integer'],
            [['CB08_NOME'], 'string', 'max' => 20],
            [['CB08_URL_IMG'], 'string', 'max' => 50],
            
            
        ]);
    }
	
}
