<?php

namespace common\models;

use Yii;
use common\models\base\SYS01PARAMETROSGLOBAIS as BaseSYS01PARAMETROSGLOBAIS;

/**
 * This is the model class for table "SYS01_PARAMETROS_GLOBAIS".
 */
class SYS01PARAMETROSGLOBAIS extends BaseSYS01PARAMETROSGLOBAIS {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['SYS01_NOME', 'SYS01_COD', 'SYS01_VALOR'], 'required'],
            [['SYS01_VALOR'], 'string'],
            [['SYS01_NOME'], 'string', 'max' => 50],
            [['SYS01_COD'], 'string', 'max' => 7],
            [['SYS01_NOME'], 'unique'],
            [['SYS01_COD'], 'unique'],
        ]);
    }

    /**
     * @inheritdoc
     * @return 
     */
    public static function getValor($cod) {
        return self::findOne(['SYS01_COD' => $cod])['SYS01_VALOR'];
    }
    
    /**
     * @inheritdoc
     * @return 
     */
    public static function setValor($cod, $value) {
        $param = self::findOne(['SYS01_COD' => $cod]);
        $param->setAttribute('SYS01_VALOR', $value);
        $param->save();
    }

}
