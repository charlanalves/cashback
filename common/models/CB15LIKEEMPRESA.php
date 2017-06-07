<?php

namespace common\models;

use Yii;
use common\models\base\CB15LIKEEMPRESA as BaseCB15LIKEEMPRESA;

/**
 * This is the model class for table "CB15_LIKE_EMPRESA".
 */
class CB15LIKEEMPRESA extends BaseCB15LIKEEMPRESA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'required'],
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'integer'],
            
            
        ]);
    }
	
}
