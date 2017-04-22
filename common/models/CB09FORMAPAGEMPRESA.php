<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB09_FORMA_PAG_EMPRESA".
 *
 * @property integer $CB09_EMPRESA_ID
 * @property integer $CB09_FORMA_PAG_ID
 *
 * @property CB04EMPRESA $cB09EMPRESA
 * @property CB08FORMAPAGAMENTO $cB09FORMAPAG
 */
class CB09FORMAPAGEMPRESA extends \common\models\GlobalModel
{
    public $FORMAPAGAMENTO;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB09_FORMA_PAG_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FORMAPAGAMENTO'], 'safe'],
            [['CB09_EMPRESA_ID', 'CB09_FORMA_PAG_ID'], 'required'],
            [['CB09_EMPRESA_ID', 'CB09_FORMA_PAG_ID'], 'integer'],
            [['CB09_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB09_EMPRESA_ID' => 'CB04_ID']],
            [['CB09_FORMA_PAG_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB08FORMAPAGAMENTO::className(), 'targetAttribute' => ['CB09_FORMA_PAG_ID' => 'CB08_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB09_EMPRESA_ID' => Yii::t('app', 'Cb09  Empresa  ID'),
            'CB09_FORMA_PAG_ID' => Yii::t('app', 'Cb09  Forma  Pag  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB09_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09FORMAPAG()
    {
        return $this->hasOne(CB08FORMAPAGAMENTO::className(), ['CB08_ID' => 'CB09_FORMA_PAG_ID']);
    }

    /**
     * @inheritdoc
     * @return CB09FORMAPAGEMPRESAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB09FORMAPAGEMPRESAQuery(get_called_class());
    }

}
