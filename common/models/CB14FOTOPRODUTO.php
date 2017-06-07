<?php

namespace common\models;

use Yii;
use common\models\base\CB14FOTOPRODUTO as BaseCB14FOTOPRODUTO;

/**
 * This is the model class for table "CB14_FOTO_PRODUTO".
 */
class CB14FOTOPRODUTO extends BaseCB14FOTOPRODUTO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB14_PRODUTO_ID', 'CB14_CAPA', 'CB14_URL'], 'required'],
            [['CB14_PRODUTO_ID', 'CB14_CAPA'], 'integer'],
            [['CB14_URL'], 'string', 'max' => 50],
            
            
        ]);
    }
	
}
