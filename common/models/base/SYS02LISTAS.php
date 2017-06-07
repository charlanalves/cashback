<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "SYS02_LISTAS".
 *
 * @property integer $SYS02_ID
 * @property integer $SYS02_COD
 * @property integer $SYS02_CAMPO_VALOR
 * @property string $SYS02_CAMPO_TXT
 */
class SYS02LISTAS extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SYS02_COD', 'SYS02_CAMPO_VALOR'], 'integer'],
            [['SYS02_CAMPO_TXT'], 'string', 'max' => 50],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SYS02_LISTAS';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SYS02_ID' => 'Sys02  ID',
            'SYS02_COD' => 'Sys02  Cod',
            'SYS02_CAMPO_VALOR' => 'Sys02  Campo  Valor',
            'SYS02_CAMPO_TXT' => 'Sys02  Campo  Txt',
        ];
    }


}
