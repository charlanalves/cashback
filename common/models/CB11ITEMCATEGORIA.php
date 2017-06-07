<?php

namespace common\models;

use Yii;
use common\models\base\CB11ITEMCATEGORIA as BaseCB11ITEMCATEGORIA;

/**
 * This is the model class for table "CB11_ITEM_CATEGORIA".
 */
class CB11ITEMCATEGORIA extends BaseCB11ITEMCATEGORIA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB11_CATEGORIA_ID', 'CB11_DESCRICAO'], 'required'],
            [['CB11_CATEGORIA_ID', 'CB11_STATUS'], 'integer'],
            [['CB11_DESCRICAO'], 'string', 'max' => 30],
            
            
        ]);
    }
	
}
