<?php

namespace common\models;

use Yii;
use common\models\base\CB13FOTOEMPRESA as BaseCB13FOTOEMPRESA;

/**
 * This is the model class for table "CB13_FOTO_EMPRESA".
 */
class CB13FOTOEMPRESA extends BaseCB13FOTOEMPRESA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB13_EMPRESA_ID', 'CB13_CAMPA', 'CB13_URL'], 'required'],
            [['CB13_EMPRESA_ID', 'CB13_CAMPA'], 'integer'],
            [['CB13_URL'], 'string', 'max' => 50],
            
            
        ]);
    }
	
}
