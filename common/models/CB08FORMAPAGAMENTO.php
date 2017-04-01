<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB08_FORMA_PAGAMENTO".
 *
 * @property integer $CB08_ID
 * @property string $CB08_NOME
 * @property string $CB08_URL_IMG
 * @property integer $CB08_STATUS
 *
 * @property CB09FORMAPAGEMPRESA[] $cB09FORMAPAGEMPRESAs
 * @property CB04EMPRESA[] $cB09EMPRESAs
 */
class CB08FORMAPAGAMENTO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB08_FORMA_PAGAMENTO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB08_NOME', 'CB08_URL_IMG'], 'required'],
            [['CB08_STATUS'], 'integer'],
            [['CB08_NOME'], 'string', 'max' => 15],
            [['CB08_URL_IMG'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB08_ID' => Yii::t('app', 'Cb08  ID'),
            'CB08_NOME' => Yii::t('app', 'Cb08  Nome'),
            'CB08_URL_IMG' => Yii::t('app', 'Cb08  Url  Img'),
            'CB08_STATUS' => Yii::t('app', 'Cb08  Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getcB09_FORMA_PAG_EMPRESA()
    {
        return $this->hasMany(CB09FORMAPAGEMPRESA::className(), ['CB09_FORMA_PAG_ID' => 'CB08_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09EMPRESAs()
    {
        return $this->hasMany(CB04EMPRESA::className(), ['CB04_ID' => 'CB09_EMPRESA_ID'])->viaTable('CB09_FORMA_PAG_EMPRESA', ['CB09_FORMA_PAG_ID' => 'CB08_ID']);
    }

    /**
     * @inheritdoc
     * @return CB08FORMAPAGAMENTOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB08FORMAPAGAMENTOQuery(get_called_class());
    }
}
