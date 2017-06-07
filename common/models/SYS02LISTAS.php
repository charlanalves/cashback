<?php

namespace common\models;

use Yii;
use common\models\base\SYS02LISTAS as BaseSYS02LISTAS;

/**
 * This is the model class for table "SYS02_LISTAS".
 */
class SYS02LISTAS extends BaseSYS02LISTAS
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['SYS02_COD', 'SYS02_CAMPO_VALOR'], 'integer'],
            [['SYS02_CAMPO_TXT'], 'string', 'max' => 50],
            
            
        ]);
    }
	
}
