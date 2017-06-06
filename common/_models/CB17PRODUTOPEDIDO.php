<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB17_PRODUTO_PEDIDO".
 *
 * @property integer $CB17_ID
 * @property integer $CB17_PRODUTO_ID
 * @property integer $CB17_PEDIDO_ID
 * @property string $CB17_NOME_PRODUTO
 * @property integer $CB17_QTD
 * @property string $CB17_VLR_UNID
 * @property integer $CB17_VARIACAO_ID
 *
 * @property CB16PEDIDO $cB17PEDIDO
 * @property CB05PRODUTO $cB17PRODUTO
 * @property CB06VARIACAO $cB17VARIACAO
 */
class CB17PRODUTOPEDIDO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB17_PRODUTO_PEDIDO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_NOME_PRODUTO', 'CB17_QTD', 'CB17_VLR_UNID'], 'required'],
            [['CB17_PRODUTO_ID', 'CB17_PEDIDO_ID', 'CB17_QTD', 'CB17_VARIACAO_ID'], 'integer'],
            [['CB17_VLR_UNID'], 'number'],
            [['CB17_NOME_PRODUTO'], 'string', 'max' => 100],
            [['CB17_PEDIDO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB16PEDIDO::className(), 'targetAttribute' => ['CB17_PEDIDO_ID' => 'CB16_ID']],
            [['CB17_PRODUTO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB05PRODUTO::className(), 'targetAttribute' => ['CB17_PRODUTO_ID' => 'CB05_ID']],
            [['CB17_VARIACAO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB06VARIACAO::className(), 'targetAttribute' => ['CB17_VARIACAO_ID' => 'CB06_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB17_ID' => Yii::t('app', 'Cb17  ID'),
            'CB17_PRODUTO_ID' => Yii::t('app', 'Cb17  Produto  ID'),
            'CB17_PEDIDO_ID' => Yii::t('app', 'Cb17  Pedido  ID'),
            'CB17_NOME_PRODUTO' => Yii::t('app', 'Cb17  Nome  Produto'),
            'CB17_QTD' => Yii::t('app', 'Cb17  Qtd'),
            'CB17_VLR_UNID' => Yii::t('app', 'Cb17  Vlr  Unid'),
            'CB17_VARIACAO_ID' => Yii::t('app', 'Cb17  Variacao  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PEDIDO()
    {
        return $this->hasOne(CB16PEDIDO::className(), ['CB16_ID' => 'CB17_PEDIDO_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTO()
    {
        return $this->hasOne(CB05PRODUTO::className(), ['CB05_ID' => 'CB17_PRODUTO_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17VARIACAO()
    {
        return $this->hasOne(CB06VARIACAO::className(), ['CB06_ID' => 'CB17_VARIACAO_ID']);
    }

    /**
     * @inheritdoc
     * @return CB17PRODUTOPEDIDOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB17PRODUTOPEDIDOQuery(get_called_class());
    }
}
