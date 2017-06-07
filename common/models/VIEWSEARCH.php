<?php

namespace common\models;

use Yii;
use common\models\base\VIEWSEARCH as BaseVIEWSEARCH;

/**
 * This is the model class for table "VIEW_SEARCH".
 */
class VIEWSEARCH extends BaseVIEWSEARCH
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['EMPRESA_ID'], 'integer'],
            [['EMPRESA_NOME', 'BUSCA_TEXTO'], 'string', 'max' => 50],
            [['TIPO'], 'string', 'max' => 7],
            [['IMG'], 'string', 'max' => 100],
            
            
        ]);
    }
	
}
