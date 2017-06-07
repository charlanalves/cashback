<?php

namespace common\models;

use Yii;
use common\models\base\PAG02ITEMTRANSACAO as BasePAG02ITEMTRANSACAO;

/**
 * This is the model class for table "PAG02_ITEM_TRANSACAO".
 */
class PAG02ITEMTRANSACAO extends BasePAG02ITEMTRANSACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG02_ITEM_COD', 'PAG02_ITEM_DESC', 'PAG02_ITEM_VLR', 'PAG02_ID_TRANSACAO'], 'required'],
            [['PAG02_ITEM_QTD', 'PAG02_ITEM_STATUS', 'PAG02_ID_TRANSACAO'], 'integer'],
            [['PAG02_ITEM_VLR'], 'number'],
            [['PAG02_ITEM_DT_INCLUSAO'], 'safe'],
            [['PAG02_ITEM_COD', 'PAG02_ITEM_DESC'], 'string', 'max' => 100],
            
            
        ]);
    }
	
}
