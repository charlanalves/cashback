<?php

namespace common\models;

use Yii;
use common\models\base\CB23TIPOAVALIACAO as BaseCB23TIPOAVALIACAO;

/**
 * This is the model class for table "CB23_TIPO_AVALIACAO".
 */
class CB23TIPOAVALIACAO extends BaseCB23TIPOAVALIACAO {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['CB23_CATEGORIA_ID', 'CB23_DESCRICAO', 'CB23_ICONE'], 'required'],
            [['CB23_CATEGORIA_ID', 'CB23_STATUS'], 'integer'],
            [['CB23_DESCRICAO'], 'string', 'max' => 100],
            [['CB23_ICONE'], 'string', 'max' => 50]
        ]);
    }

}
