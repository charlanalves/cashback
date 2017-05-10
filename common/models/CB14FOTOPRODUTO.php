<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB14_FOTO_PRODUTO".
 *
 * @property integer $CB14_ID
 * @property integer $CB14_PRODUTO_ID
 * @property integer $CB14_CAPA
 * @property string $CB14_URL
 *
 * @property CB05PRODUTO $cB14PRODUTO
 */
class CB14FOTOPRODUTO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB14_FOTO_PRODUTO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB14_PRODUTO_ID', 'CB14_URL'], 'required'],
            [['CB14_PRODUTO_ID', 'CB14_CAPA'], 'integer'],
            [['CB14_URL'], 'string', 'max' => 50],
            [['CB14_PRODUTO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB05PRODUTO::className(), 'targetAttribute' => ['CB14_PRODUTO_ID' => 'CB05_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB14_ID' => Yii::t('app', 'Cb14  ID'),
            'CB14_PRODUTO_ID' => Yii::t('app', 'Cb14  Produto  ID'),
            'CB14_CAPA' => Yii::t('app', 'Cb14  Capa'),
            'CB14_URL' => Yii::t('app', 'Cb14  Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB14PRODUTO()
    {
        return $this->hasOne(CB05PRODUTO::className(), ['CB05_ID' => 'CB14_PRODUTO_ID']);
    }

    /**
     * @inheritdoc
     * @return CB14FOTOPRODUTOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB14FOTOPRODUTOQuery(get_called_class());
    }
}
