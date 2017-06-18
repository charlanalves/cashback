<?php

namespace common\models\;

use Yii;
use common\models\base\VIEWEXTRATO as BaseVIEWEXTRATO;

/**
 * This is the model class for table "VIEW_EXTRATO".
 */
class VIEWEXTRATO extends BaseVIEWEXTRATO
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
