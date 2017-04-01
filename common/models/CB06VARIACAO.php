<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB06_VARIACAO".
 *
 * @property integer $CB06_ID
 * @property integer $CB06_PRODUTO_ID
 * @property string $CB06_DESCRICAO
 * @property string $CB06_PRECO
 *
 * @property CB07CASHBACK[] $cB07CASHBACKs
 */
class CB06VARIACAO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB06_VARIACAO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB06_PRODUTO_ID', 'CB06_DESCRICAO', 'CB06_PRECO'], 'required'],
            [['CB06_PRODUTO_ID'], 'integer'],
            [['CB06_PRECO'], 'number'],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => Yii::t('app', 'Cb06  ID'),
            'CB06_PRODUTO_ID' => Yii::t('app', 'Cb06  Produto  ID'),
            'CB06_DESCRICAO' => Yii::t('app', 'Cb06  Descricao'),
            'CB06_PRECO' => Yii::t('app', 'Cb06  Preco'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs()
    {
        return $this->hasMany(CB07CASHBACK::className(), ['CB07_VARIACAO_ID' => 'CB06_ID']);
    }

    /**
     * @inheritdoc
     * @return CB06VARIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB06VARIACAOQuery(get_called_class());
    }
}
