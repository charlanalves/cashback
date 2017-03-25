<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB07_CASH_BACK".
 *
 * @property integer $CB07_ID
 * @property integer $CB07_PRODUTO_ID
 * @property integer $CB07_VARIACAO_ID
 * @property integer $CB07_DIA_SEMANA
 * @property string $CB07_PERCENTUAL
 *
 * @property CB05PRODUTO $cB07PRODUTO
 * @property CB06VARIACAO $cB07VARIACAO
 */
class CB07CASHBACK extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB07_CASH_BACK';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB07_PRODUTO_ID', 'CB07_VARIACAO_ID', 'CB07_DIA_SEMANA'], 'integer'],
            [['CB07_PERCENTUAL'], 'required'],
            [['CB07_PERCENTUAL'], 'number'],
            [['CB07_PRODUTO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB05PRODUTO::className(), 'targetAttribute' => ['CB07_PRODUTO_ID' => 'CB05_ID']],
            [['CB07_VARIACAO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB06VARIACAO::className(), 'targetAttribute' => ['CB07_VARIACAO_ID' => 'CB06_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB07_ID' => Yii::t('app', 'Cb07  ID'),
            'CB07_PRODUTO_ID' => Yii::t('app', 'Cb07  Produto  ID'),
            'CB07_VARIACAO_ID' => Yii::t('app', 'Cb07  Variacao  ID'),
            'CB07_DIA_SEMANA' => Yii::t('app', 'Cb07  Dia  Semana'),
            'CB07_PERCENTUAL' => Yii::t('app', 'Cb07  Percentual'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07PRODUTO()
    {
        return $this->hasOne(CB05PRODUTO::className(), ['CB05_ID' => 'CB07_PRODUTO_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07VARIACAO()
    {
        return $this->hasOne(CB06VARIACAO::className(), ['CB06_ID' => 'CB07_VARIACAO_ID']);
    }

    /**
     * @inheritdoc
     * @return CB07CASHBACKQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB07CASHBACKQuery(get_called_class());
    }
}
