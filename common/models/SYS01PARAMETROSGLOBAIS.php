<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SYS01_PARAMETROS_GLOBAIS".
 *
 * @property integer $SYS01_ID
 * @property string $SYS01_NOME
 * @property string $SYS01_COD
 * @property string $SYS01_VALOR
 */
class SYS01PARAMETROSGLOBAIS extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SYS01_PARAMETROS_GLOBAIS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SYS01_NOME', 'SYS01_COD', 'SYS01_VALOR'], 'required'],
            [['SYS01_VALOR'], 'string'],
            [['SYS01_NOME'], 'string', 'max' => 50],
            [['SYS01_COD'], 'string', 'max' => 5],
            [['SYS01_NOME'], 'unique'],
            [['SYS01_COD'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SYS01_ID' => Yii::t('app', 'Sys01  ID'),
            'SYS01_NOME' => Yii::t('app', 'Sys01  Nome'),
            'SYS01_COD' => Yii::t('app', 'Sys01  Cod'),
            'SYS01_VALOR' => Yii::t('app', 'Sys01  Valor'),
        ];
    }

    /**
     * @inheritdoc
     * @return SYS01PARAMETROSGLOBAISQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SYS01PARAMETROSGLOBAISQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * @return 
     */
    public static function getValor($cod)
    {
        return self::findOne(['SYS01_COD' => $cod])['SYS01_VALOR'];
    }
}
