<?php

namespace common\models;

use Yii;
use common\models\base\CB12ITEMCATEGEMPRESA as BaseCB12ITEMCATEGEMPRESA;

/**
 * This is the model class for table "CB12_ITEM_CATEG_EMPRESA".
 */
class CB12ITEMCATEGEMPRESA extends BaseCB12ITEMCATEGEMPRESA
{
	public $ITEM;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB12_ITEM_ID'], 'required'],
            [['CB12_ITEM_ID', 'CB12_EMPRESA_ID', 'CB12_PRODUTO_ID'], 'integer'],
            
            
        ]);
    }
	
}
