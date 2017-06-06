<?php

namespace common\models;

use Yii;
use common\models\base\CB10CATEGORIA as BaseCB10CATEGORIA;

/**
 * This is the model class for table "CB10_CATEGORIA".
 */
class CB10CATEGORIA extends BaseCB10CATEGORIA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB10_NOME'], 'required'],
            [['CB10_STATUS'], 'integer'],
            [['CB10_NOME'], 'string', 'max' => 30],
            
            
        ]);
    }
	
}
