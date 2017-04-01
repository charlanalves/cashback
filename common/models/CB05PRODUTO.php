<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB05_PRODUTO".
 *
 * @property integer $CB05_ID
 * @property integer $CB05_EMPRESA_ID
 * @property string $CB05_NOME_CURTO
 * @property string $CB05_TITULO
 * @property string $CB05_DESCRICAO
 * @property string $CB05_IMPORTANTE
 *
 * @property CB04EMPRESA $cB05EMPRESA
 * @property CB07CASHBACK[] $cB07CASHBACKs
 * @property CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property CB14FOTOPRODUTO[] $cB14FOTOPRODUTOs
 */
class CB05PRODUTO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB05_PRODUTO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB05_EMPRESA_ID', 'CB05_NOME_CURTO', 'CB05_TITULO'], 'required'],
            [['CB05_EMPRESA_ID'], 'integer'],
            [['CB05_DESCRICAO', 'CB05_IMPORTANTE'], 'string'],
            [['CB05_NOME_CURTO'], 'string', 'max' => 15],
            [['CB05_TITULO'], 'string', 'max' => 30],
            [['CB05_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB05_EMPRESA_ID' => 'CB04_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB05_ID' => Yii::t('app', 'Cb05  ID'),
            'CB05_EMPRESA_ID' => Yii::t('app', 'Cb05  Empresa  ID'),
            'CB05_TITULO' => Yii::t('app', 'Cb05  Titulo'),
            'CB05_NOME_CURTO' => Yii::t('app', 'Cb05  Nome Curto'),
            'CB05_DESCRICAO' => Yii::t('app', 'Cb05  Descricao'),
            'CB05_IMPORTANTE' => Yii::t('app', 'Cb05  Importante'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB05EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB05_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs()
    {
        return $this->hasMany(CB07CASHBACK::className(), ['CB07_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(CB12ITEMCATEGEMPRESA::className(), ['CB12_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB14FOTOPRODUTOs()
    {
        return $this->hasMany(CB14FOTOPRODUTO::className(), ['CB14_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @inheritdoc
     * @return CB05PRODUTOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB05PRODUTOQuery(get_called_class());
    }
}
