<?php

namespace common\models;

use Yii;
use common\models\base\PAG03ADQUIRENTES as BasePAG03ADQUIRENTES;

/**
 * This is the model class for table "PAG03_ADQUIRENTES".
 */
class PAG03ADQUIRENTES extends BasePAG03ADQUIRENTES
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG03_ID', 'PAG03_NOME', 'PAG03_PERC_DEBTO_ECO', 'PAG03_PERC_DEBTO_POS', 'PAG03_PERC_CREDITO_ECO', 'PAG03_PERC_CREDITO_POS', 'PAG03_VLR_ANTI_FRAUDE', 'PAG03_OUTROS_VLR'], 'required'],
            [['PAG03_ID'], 'integer'],
            [['PAG03_PERC_DEBTO_ECO', 'PAG03_PERC_DEBTO_POS', 'PAG03_PERC_CREDITO_ECO', 'PAG03_PERC_CREDITO_POS', 'PAG03_VLR_ANTI_FRAUDE', 'PAG03_OUTROS_VLR'], 'number'],
            [['PAG03_NOME'], 'string', 'max' => 50],
            
            
        ]);
    }
	
}
