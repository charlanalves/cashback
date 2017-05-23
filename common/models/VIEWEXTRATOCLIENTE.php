<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "VIEW_EXTRATO_CLIENTE".
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
    public static function tableName()
    {
        return 'VIEW_EXTRATO_CLIENTE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DT1'], 'safe'],
            [['VLR1'], 'number'],
            [['STATUS', 'CLIENTE'], 'integer'],
            [['TRANSACAO_ID', 'TRANSFERENCIA_ID', 'EMPRESA_ID'], 'string', 'max' => 11],
            [['TIPO'], 'string', 'max' => 13],
            [['EMPRESA_NM'], 'string', 'max' => 50],
            [['DT2'], 'string', 'max' => 19],
            [['VLR2'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TRANSACAO_ID' => Yii::t('app', 'Transacao  ID'),
            'TRANSFERENCIA_ID' => Yii::t('app', 'Transferencia  ID'),
            'TIPO' => Yii::t('app', 'Tipo'),
            'EMPRESA_ID' => Yii::t('app', 'Empresa  ID'),
            'EMPRESA_NM' => Yii::t('app', 'Empresa  Nm'),
            'DT1' => Yii::t('app', 'Dt1'),
            'DT2' => Yii::t('app', 'Dt2'),
            'VLR1' => Yii::t('app', 'Vlr1'),
            'VLR2' => Yii::t('app', 'Vlr2'),
            'STATUS' => Yii::t('app', 'Status'),
            'CLIENTE' => Yii::t('app', 'Cliente'),
        ];
    }

    /**
     * @inheritdoc
     * @return VIEWEXTRATOCLIENTEQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VIEWEXTRATOCLIENTEQuery(get_called_class());
    }
    
    
    public static function saldoAtualByCliente($cliente)
    {
        $sql = "SELECT SUM(VLR2) AS SALDO FROM VIEW_EXTRATO_CLIENTE WHERE CLIENTE = :cliente";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':cliente', $cliente);
        return $command->queryOne()['SALDO'];
    }
    
    
    public static function saldoAtualByAuthKey($AuthKey)
    {
        return self::saldoAtualByCliente(User::getIdByAuthKey($AuthKey));
    }
    
    
}
