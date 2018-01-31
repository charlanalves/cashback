<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "CB09_FORMA_PAGTO_EMPRESA".
 *
 * @property integer $CB09_ID
 * @property integer $CB09_ID_EMPRESA
 * @property integer $CB09_ID_FORMA_PAG
 * @property string $CB09_PERC_ADQ
 * @property string $CB09_PERC_ADMIN
 * @property string CB09_PERC_FUNCIONARIO
 * @property string CB09_PERC_REPRESENTANTE
 * @property string CB09_PERC_FUNC_ADMIN
 *
 * @property common\models\CB04EMPRESA $cB09IDEMPRESA
 * @property common\models\CB08FORMAPAGAMENTO $cB09IDFORMAPAG
 * @property common\models\CB16PEDIDO[] $cB16PEDIDOs
 */
class CB09FORMAPAGTOEMPRESA extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG', 'CB09_PERC_ADQ', 'CB09_PERC_ADMIN', 'CB09_PERC_FUNCIONARIO', 'CB09_PERC_REPRESENTANTE', 'CB09_PERC_FUNC_ADMIN'], 'required'],
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG'], 'integer'],
            [['CB09_PERC_ADQ', 'CB09_PERC_ADMIN', 'CB09_PERC_FUNCIONARIO', 'CB09_PERC_REPRESENTANTE', 'CB09_PERC_FUNC_ADMIN'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB09_FORMA_PAGTO_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB09_ID' => 'ID',
            'CB09_ID_EMPRESA' => 'Empresa',
            'CB09_ID_FORMA_PAG' => 'Forma de pagamento',
            'CB09_PERC_ADQ' => 'Adquirente',
            'CB09_PERC_ADMIN' => 'Admin',
            'CB09_PERC_FUNCIONARIO' => 'Funcionário Emp.',
            'CB09_PERC_REPRESENTANTE' => 'Representante',
            'CB09_PERC_FUNC_ADMIN' => 'Funcionário Admin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09IDEMPRESA()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB09_ID_EMPRESA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09IDFORMAPAG()
    {
        return $this->hasOne(\common\models\CB08FORMAPAGAMENTO::className(), ['CB08_ID' => 'CB09_ID_FORMA_PAG']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16PEDIDOs()
    {
        return $this->hasMany(\common\models\CB16PEDIDO::className(), ['CB16_ID_FORMA_PAG_EMPRESA' => 'CB09_ID']);
    }

}
