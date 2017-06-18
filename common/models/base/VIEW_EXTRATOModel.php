<?php

namespace common\models\base;

use Yii;


/**
 * This is the base model class for table "VIEW_EXTRATO".
 *
 * @property integer $TRANSFERENCIA_ID
 * @property string $TIPO
 * @property integer $PEDIDO_ID
 * @property string $DT_CRIACAO
 * @property string $DT_PREVISAO
 * @property string $DT_DEPOSITO
 * @property string $VALOR
 * @property integer $USER
 */
class VIEW_EXTRATOModel extends \common\models\GlobalModel
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TRANSFERENCIA_ID', 'PEDIDO_ID', 'USER'], 'integer'],
            [['DT_CRIACAO', 'DT_PREVISAO', 'DT_DEPOSITO'], 'safe'],
            [['VALOR'], 'number'],
            [['TIPO'], 'string', 'max' => 5],
         
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIEW_EXTRATO';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TRANSFERENCIA_ID' => 'Transferencia  ID',
            'TIPO' => 'Tipo',
            'PEDIDO_ID' => 'Pedido  ID',
            'DT_CRIACAO' => 'Dt  Criacao',
            'DT_PREVISAO' => 'Dt  Previsao',
            'DT_DEPOSITO' => 'Dt  Deposito',
            'VALOR' => 'Valor',
            'USER' => 'User',
        ];
    }



}
