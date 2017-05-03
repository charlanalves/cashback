<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB16_PEDIDO".
 *
 * @property integer $CB16_ID
 * @property integer $CB16_EMPRESA_ID
 * @property integer $CB16_USER_ID
 * @property string $CB16_GATEWAY
 * @property string $CB16_VALOR
 * @property string $CB16_FRETE
 * @property integer $CB16_NUM_PARCELA
 * @property integer $CB16_STATUS
 * @property string $CB16_DT
 *
 * @property CB04EMPRESA $cB16EMPRESA
 * @property User $cB16USER
 * @property CB17PRODUTOPEDIDO[] $cB17PRODUTOPEDIDOs
 */
class CB16PEDIDO extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB16_PEDIDO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_VALOR'], 'required'],
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_NUM_PARCELA', 'CB16_STATUS'], 'integer'],
            [['CB16_VALOR', 'CB16_FRETE'], 'number'],
            [['CB16_DT'], 'safe'],
            [['CB16_GATEWAY'], 'string', 'max' => 50],
            [['CB16_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB16_EMPRESA_ID' => 'CB04_ID']],
            [['CB16_USER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['CB16_USER_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB16_ID' => Yii::t('app', 'Cb16  ID'),
            'CB16_EMPRESA_ID' => Yii::t('app', 'Cb16  Empresa  ID'),
            'CB16_USER_ID' => Yii::t('app', 'Cb16  User  ID'),
            'CB16_GATEWAY' => Yii::t('app', 'Cb16  Gateway'),
            'CB16_VALOR' => Yii::t('app', 'Cb16  Valor'),
            'CB16_FRETE' => Yii::t('app', 'Cb16  Frete'),
            'CB16_NUM_PARCELA' => Yii::t('app', 'Cb16  Num  Parcela'),
            'CB16_STATUS' => Yii::t('app', 'Cb16  Status'),
            'CB16_DT' => Yii::t('app', 'Cb16  Dt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16EMPRESA()
    {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB16_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16USER()
    {
        return $this->hasOne(User::className(), ['id' => 'CB16_USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTOPEDIDOs()
    {
        return $this->hasMany(CB17PRODUTOPEDIDO::className(), ['CB17_PEDIDO_ID' => 'CB16_ID']);
    }

    /**
     * @inheritdoc
     * @return CB16PEDIDOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB16PEDIDOQuery(get_called_class());
    }
    
    public static function getPedido($pedido, $usuario)
    {
        
        $sql = "SELECT CB16_PEDIDO.*, CB17_PRODUTO_PEDIDO.*, CB04_EMPRESA.*, CB14_FOTO_PRODUTO.CB14_URL 
                FROM CB16_PEDIDO 
                INNER JOIN CB17_PRODUTO_PEDIDO ON(CB16_PEDIDO.CB16_ID = CB17_PRODUTO_PEDIDO.CB17_PEDIDO_ID)
                INNER JOIN CB04_EMPRESA ON(CB16_PEDIDO.CB16_EMPRESA_ID = CB04_EMPRESA.CB04_ID)
                LEFT JOIN CB14_FOTO_PRODUTO ON(CB17_PRODUTO_PEDIDO.CB17_PRODUTO_ID =  CB14_FOTO_PRODUTO.CB14_PRODUTO_ID AND CB14_FOTO_PRODUTO.CB14_CAPA = 1)
                WHERE CB16_ID = :pedido AND CB16_USER_ID = :usuario";

        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':pedido', $pedido);
        $command->bindValue(':usuario', $usuario);
        return $command->query()->readAll()[0];
        
    }
    
}
