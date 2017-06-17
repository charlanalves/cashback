<?php

namespace common\models;

use Yii;
use common\models\base\PAG04TRANSFERENCIAS as BasePAG04TRANSFERENCIAS;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class PAG04TRANSFERENCIAS extends BasePAG04TRANSFERENCIAS
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG04_DATA_CRIACAO'], 'safe'],
            [['PAG04_ID_PEDIDO', 'PAG04_VLR','PAG04_TIPO','PAG04_COD_CONTA_ORIGEM','PAG04_COD_CONTA_DESTINO'], 'required'],
            [['PAG04_ID_PEDIDO', 'PAG04_TIPO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            [['PAG04_COD_CONTA_ORIGEM', 'PAG04_COD_CONTA_DESTINO'], 'string', 'max' => 200],
            
            
        ]);
    }
	
}
