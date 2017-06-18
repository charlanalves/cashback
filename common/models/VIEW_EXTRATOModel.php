<?php

namespace common\models\;

use Yii;
use common\models\base\VIEW_EXTRATOModel as BaseVIEW_EXTRATOModel;

/**
 * This is the model class for table "VIEW_EXTRATO".
 */
class VIEW_EXTRATOModel extends BaseVIEW_EXTRATOModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['TRANSFERENCIA_ID', 'PEDIDO_ID', 'USER'], 'integer'],
            [['DT_CRIACAO', 'DT_PREVISAO', 'DT_DEPOSITO'], 'safe'],
            [['VALOR'], 'number'],
            [['TIPO'], 'string', 'max' => 5],
        ]);
    }
	
}
