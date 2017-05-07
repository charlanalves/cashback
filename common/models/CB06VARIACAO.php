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
 * @property string $CB06_PRECO_PROMOCIONAL
 * @property string $CB06_DINHEIRO_VOLTA
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
            [['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'number'],
            [['CB06_PRECO'], 'filter', 'filter' => function () {
                if($this->CB06_PRECO >= $this->CB06_PRECO_PROMOCIONAL){
                    $this->addError('CB06_PRECO_PROMOCIONAL', 'O preço promocional deve ser menor que o preço original.');
                }
            }],
            [['CB06_DINHEIRO_VOLTA'], 'filter', 'filter' => function () {
                if($this->CB06_DINHEIRO_VOLTA > 100){
                    $this->addError('CB06_DINHEIRO_VOLTA', 'O máximo de "Dinheiro de volta" permitido é 100%.');
                }
            }],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => Yii::t('app', 'ID'),
            'CB06_PRODUTO_ID' => Yii::t('app', 'Produto'),
            'CB06_DESCRICAO' => Yii::t('app', 'Descrição'),
            'CB06_PRECO' => Yii::t('app', 'Preço original'),
            'CB06_PRECO_PROMOCIONAL' => Yii::t('app', 'Preço promocional'),
            'CB06_DINHEIRO_VOLTA' => Yii::t('app', 'Dinheiro de volta'),
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
