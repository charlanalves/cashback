<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB00_TRANSFERENCIA".
 *
 * @property integer $CB00_ID
 * @property integer $CB00_CLIENTE_ID
 * @property string $CB00_DT_SOLICITACAO
 * @property string $CB00_DT_CONCLUSAO
 * @property integer $CB00_COD_BANCO
 * @property integer $CB00_TP_CONTA
 * @property integer $CB00_NUM_CONTA
 * @property string $CB00_AGENCIA
 * @property integer $CB00_STATUS
 * @property string $CB00_VALOR_TRANSFERIDO
 *
 * @property common\models\User $cB00CLIENTE
 */
class CB00TRANSFERENCIA extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB00_CLIENTE_ID', 'CB00_COD_BANCO', 'CB00_TP_CONTA', 'CB00_NUM_CONTA', 'CB00_AGENCIA', 'CB00_STATUS', 'CB00_VALOR_TRANSFERIDO'], 'required'],
            [['CB00_CLIENTE_ID', 'CB00_COD_BANCO', 'CB00_TP_CONTA', 'CB00_NUM_CONTA', 'CB00_STATUS'], 'integer'],
            [['CB00_DT_SOLICITACAO', 'CB00_DT_CONCLUSAO'], 'safe'],
            [['CB00_VALOR_TRANSFERIDO'], 'number'],
            [['CB00_AGENCIA'], 'string', 'max' => 5],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB00_TRANSFERENCIA';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB00_ID' => 'Cb00  ID',
            'CB00_CLIENTE_ID' => 'Cb00  Cliente  ID',
            'CB00_DT_SOLICITACAO' => 'Cb00  Dt  Solicitacao',
            'CB00_DT_CONCLUSAO' => 'Cb00  Dt  Conclusao',
            'CB00_COD_BANCO' => 'Cb00  Cod  Banco',
            'CB00_TP_CONTA' => 'Cb00  Tp  Conta',
            'CB00_NUM_CONTA' => 'Cb00  Num  Conta',
            'CB00_AGENCIA' => 'Cb00  Agencia',
            'CB00_STATUS' => 'Cb00  Status',
            'CB00_VALOR_TRANSFERIDO' => 'Cb00  Valor  Transferido',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB00CLIENTE()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB00_CLIENTE_ID']);
    }
    

}
