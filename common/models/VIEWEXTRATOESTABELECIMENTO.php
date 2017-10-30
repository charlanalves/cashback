<?php

namespace common\models;

use Yii;
use common\models\base\VIEWEXTRATOESTABELECIMENTO as BaseVIEWEXTRATOESTABELECIMENTO;

/**
 * This is the model class for table "VIEW_EXTRATO_ESTABELECIMENTO".
 */
class VIEWEXTRATOESTABELECIMENTO extends BaseVIEWEXTRATOESTABELECIMENTO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),[]);
    }
    
}
