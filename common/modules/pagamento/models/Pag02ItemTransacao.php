<?php

namespace common\modules\pagamento\models;

use \common\modules\pagamento\models\base\Pag02ItemTransacao as BasePag02ItemTransacao;

/**
 * This is the model class for table "pag02_item_transacao".
 */
class Pag02ItemTransacao extends BasePag02ItemTransacao
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['ITEM_COD', 'ITEM_DESC', 'ITEM_VLR', 'ID_TRANSACAO'], 'required'],
            [['ITEM_QTD', 'ITEM_STATUS', 'ID_TRANSACAO'], 'integer'],
            [['ITEM_VLR'], 'number'],
//            [['ITEM_DT_INCLUSAO'], 'safe'],
            [['ITEM_COD', 'ITEM_DESC'], 'string', 'max' => 100],
//            [['lock'], 'default', 'value' => '0'],
//            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
}
