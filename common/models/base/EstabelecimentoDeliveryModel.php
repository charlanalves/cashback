<?php

namespace common\models\base;

use \Yii;

/**
 * This is the base model class for table "CB16_PEDIDO".
 *
 */
class EstabelecimentoExtratoModel extends \common\models\GlobalModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        $this->primaryKey();
        return 'CB16_PEDIDO';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey() {
        return ['CB16_ID'];
    }

    /**
     * @inheritdoc
     */
    public static function colFlagAtivo() {
        return true;
    }

}
