<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB15_LIKE_EMPRESA".
 *
 * @property integer $CB15_EMPRESA_ID
 * @property integer $CB15_USER_ID
 *
 * @property CB04EMPRESA $cB15EMPRESA
 * @property User $cB15USER
 */
class CB15LIKEEMPRESA extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB15_LIKE_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'required'],
            [['CB15_EMPRESA_ID', 'CB15_USER_ID'], 'integer'],
            [['CB15_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB15_EMPRESA_ID' => 'CB04_ID']],
            [['CB15_USER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['CB15_USER_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB15_EMPRESA_ID' => Yii::t('app', 'Cb15  Empresa  ID'),
            'CB15_USER_ID' => Yii::t('app', 'Cb15  User  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB15_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15USER()
    {
        return $this->hasOne(User::className(), ['id' => 'CB15_USER_ID']);
    }

    /**
     * @inheritdoc
     * @return CB15LIKEEMPRESAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB15LIKEEMPRESAQuery(get_called_class());
    }
}
