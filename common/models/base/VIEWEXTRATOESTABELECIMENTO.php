<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "VIEW_EXTRATO_ESTABELECIMENTO".
 *
 */
class VIEWEXTRATOESTABELECIMENTO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIEW_EXTRATO_ESTABELECIMENTO';
    }

   

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PEDIDO_ID' => 'PEDIDO',
            'DATA_OPERACAO' => 'DATA OPERAÇÃO',
            'VALOR_VENDA' => 'VALOR VENDA',
            'TAXA_ADQUIRENTE' => 'TAXA ADQUIRENTE',
            'TAXA_ESTALECA' => 'TAXA ESTALECA',
            'DINHEIRO_VOLTA' => 'DINHEIRO DE VOLTA',
            'SALDO' => 'SALDO',
            'DATA_LIBERACAO' => 'DATA LIBERAÇÃO'
        ];
    }


}
