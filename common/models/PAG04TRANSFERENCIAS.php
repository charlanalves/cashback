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
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_DT_PREV', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO', 'PAG04_VLR', 'PAG04_TIPO'], 'required'],
            [['PAG04_ID_PEDIDO', 'PAG04_ID_USER_CONTA_ORIGEM', 'PAG04_ID_USER_CONTA_DESTINO', 'PAG04_TIPO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            
            
        ]);
    }
	
}
