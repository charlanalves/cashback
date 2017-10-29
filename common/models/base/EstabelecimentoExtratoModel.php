<?php

namespace common\models\base;

use \Yii;

/**
 * This is the base model class for table "VIEW_EXTRATO_ESTABELECIMENTO".
 *
 */
class EstabelecimentoExtratoModel extends \common\models\GlobalModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        $this->primaryKey();
        return 'VIEW_EXTRATO_ESTABELECIMENTO';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey() {
        return ['PEDIDO_ID'];
    }

    /**
     * @inheritdoc
     */
    public static function colFlagAtivo() {
        return true;
    }

}
