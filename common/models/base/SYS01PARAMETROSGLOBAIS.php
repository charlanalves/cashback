<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "SYS01_PARAMETROS_GLOBAIS".
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
    public function rules()
    {
        return [
            [['SYS01_NOME', 'SYS01_COD', 'SYS01_VALOR'], 'required'],
            [['SYS01_VALOR'], 'string'],
            [['SYS01_NOME'], 'string', 'max' => 50],
            [['SYS01_COD'], 'string', 'max' => 7],
            [['SYS01_NOME'], 'unique'],
            [['SYS01_COD'], 'unique'],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'SYS01_ID' => 'ID',
            'SYS01_NOME' => 'Nome',
            'SYS01_COD' => 'Código',
            'SYS01_VALOR' => 'Valor',
        ];
    }


}
