<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB02_CLIENTE".
 *
 * @property integer $CB02_ID
 * @property string $CB02_NOME
 * @property string $CB02_CPF
 * @property string $CB02_EMAIL
 * @property integer $CB02_STATUS
 * @property string $CB02_DT_CADASTRO
 *
 * @property CB00TRANSFERENCIA[] $cB00TRANSFERENCIAs
 * @property CB01TRANSACAO[] $cB01TRANSACAOs
 * @property CB03CONTABANC[] $cB03CONTABANCs
 */
class CB02CLIENTE extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB02_CLIENTE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB02_NOME', 'CB02_CPF', 'CB02_EMAIL'], 'required'],
            [['CB02_STATUS'], 'integer'],
            [['CB02_DT_CADASTRO'], 'safe'],
            [['CB02_NOME', 'CB02_EMAIL'], 'string', 'max' => 50],
            [['CB02_CPF'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB02_ID' => Yii::t('app', 'Cb02  ID'),
            'CB02_NOME' => Yii::t('app', 'Cb02  Nome'),
            'CB02_CPF' => Yii::t('app', 'Cb02  Cpf'),
            'CB02_EMAIL' => Yii::t('app', 'Cb02  Email'),
            'CB02_STATUS' => Yii::t('app', 'Cb02  Status'),
            'CB02_DT_CADASTRO' => Yii::t('app', 'Cb02  Dt  Cadastro'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB00TRANSFERENCIAs()
    {
        return $this->hasMany(CB00TRANSFERENCIA::className(), ['CB00_CLIENTE_ID' => 'CB02_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01TRANSACAOs()
    {
        return $this->hasMany(CB01TRANSACAO::className(), ['CB01_CLIENTE_ID' => 'CB02_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB03CONTABANCs()
    {
        return $this->hasMany(CB03CONTABANC::className(), ['CB03_CLIENTE_ID' => 'CB02_ID']);
    }

    /**
     * @inheritdoc
     * @return CB02CLIENTEQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB02CLIENTEQuery(get_called_class());
    }
}
