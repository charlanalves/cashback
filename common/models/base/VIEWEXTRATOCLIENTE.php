<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "VIEW_EXTRATO_CLIENTE".
 *
 * @property string $TRANSACAO_ID
 * @property string $TRANSFERENCIA_ID
 * @property string $TIPO
 * @property string $EMPRESA_ID
 * @property string $EMPRESA_NM
 * @property string $DT1
 * @property string $DT2
 * @property string $VLR1
 * @property string $VLR2
 * @property integer $STATUS
 * @property integer $CLIENTE
 */
class VIEWEXTRATOCLIENTE extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DT1'], 'safe'],
            [['VLR1', 'VLR2'], 'number'],
            [['STATUS', 'CLIENTE'], 'integer'],
            [['TRANSACAO_ID', 'TRANSFERENCIA_ID', 'EMPRESA_ID'], 'string', 'max' => 11],
            [['TIPO'], 'string', 'max' => 13],
            [['EMPRESA_NM'], 'string', 'max' => 50],
            [['DT2'], 'string', 'max' => 19],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIEW_EXTRATO_CLIENTE';
    }

   

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TRANSACAO_ID' => 'Transacao  ID',
            'TRANSFERENCIA_ID' => 'Transferencia  ID',
            'TIPO' => 'Tipo',
            'EMPRESA_ID' => 'Empresa  ID',
            'EMPRESA_NM' => 'Empresa  Nm',
            'DT1' => 'Dt1',
            'DT2' => 'Dt2',
            'VLR1' => 'Vlr1',
            'VLR2' => 'Vlr2',
            'STATUS' => 'Status',
            'CLIENTE' => 'Cliente',
        ];
    }


}
