<?php

namespace common\models;

use Yii;
use common\models\base\CB09FORMAPAGTOEMPRESA as BaseCB09FORMAPAGTOEMPRESA;

/**
 * This is the model class for table "CB09_FORMA_PAGTO_EMPRESA".
 */
class CB09FORMAPAGTOEMPRESA extends BaseCB09FORMAPAGTOEMPRESA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG', 'CB09_PERC_ADQ', 'CB09_PERC_ADMIN'], 'required'],
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG'], 'integer'],
            [['CB09_PERC_ADQ', 'CB09_PERC_ADMIN'], 'number'],
            
            
        ]);
    }
	
}
