<?php

namespace common\models;

use Yii;
use common\models\base\CB07CASHBACK as BaseCB07CASHBACK;

/**
 * This is the model class for table "CB07_CASH_BACK".
 */
class CB07CASHBACK extends BaseCB07CASHBACK
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB07_PRODUTO_ID', 'CB07_VARIACAO_ID', 'CB07_DIA_SEMANA'], 'integer'],
            [['CB07_DIA_SEMANA', 'CB07_PERCENTUAL'], 'required'],
            [['CB07_PERCENTUAL'], 'number'],
            
            
        ]);
    }
	
}
