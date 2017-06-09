<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB16_PEDIDO".
 *
 * @property integer $CB16_ID
 * @property integer $CB16_EMPRESA_ID
 * @property integer $CB16_USER_ID
 * @property integer $CB16_ID_COMPRADOR
 * @property string $CB16_GATEWAY
 * @property integer $CB16_ID_FORMA_PAG_EMPRESA
 * @property string $CB16_VALOR
 * @property string $CB16_FRETE
 * @property integer $CB16_NUM_PARCELA
 * @property integer $CB16_STATUS
 * @property string $CB16_DT
 *
 * @property common\models\CB02CLIENTE $cB16IDCOMPRADOR
 * @property common\models\User $cB16USER
 * @property common\models\CB09FORMAPAGTOEMPRESA $cB16IDFORMAPAGEMPRESA
 * @property common\models\CB17PRODUTOPEDIDO[] $cB17PRODUTOPEDIDOs
 */
class CB16PEDIDO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['CB16_TRANS_CRIADAS', 'CB16_EMPRESA_ID', 'CB16_ID_COMPRADOR', 'CB16_USER_ID', 'CB16_ID_FORMA_PAG_EMPRESA', 'CB16_STATUS', 'CB16_CARTAO_NUM_PARCELA'], 'integer'],
            [['CB16_EMPRESA_ID', 'CB16_USER_ID', 'CB16_VALOR', 'CB16_VLR_CB_TOTAL'], 'required'],
            [['CB16_VALOR', 'CB16_PERC_ADMIN', 'CB16_PERC_ADQ', 'CB16_VLR_CB_TOTAL', 'CB16_FRETE', 'CB16_CARTAO_VLR_PARCELA'], 'number'],
            [['CB16_DT', 'CB16_DT_APROVACAO'], 'safe'],
            [['CB16_CARTAO_TOKEN'], 'string'],
            [['CB16_FORMA_PAG'], 'string', 'max' => 50],
            [['CB16_COMPRADOR_NOME', 'CB16_COMPRADOR_EMAIL', 'CB16_COMPRADOR_CPF', 'CB16_COMPRADOR_TEL_DDD', 'CB16_COMPRADOR_TEL_NUMERO', 'CB16_COMPRADOR_END_LOGRADOURO', 'CB16_COMPRADOR_END_NUMERO', 'CB16_COMPRADOR_END_BAIRRO', 'CB16_COMPRADOR_END_CEP', 'CB16_COMPRADOR_END_CIDADE', 'CB16_COMPRADOR_END_UF'], 'string', 'max' => 100],
            [['CB16_COMPRADOR_END_PAIS'], 'string', 'max' => 2],
            [['CB16_COMPRADOR_END_COMPLEMENTO'], 'string', 'max' => 500],
            
            
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'CB16_ID' => 'Cb16  ID',
            'CB16_EMPRESA_ID' => 'Cb16  Empresa  ID',
            'CB16_USER_ID' => 'Cb16  User  ID',
            'CB16_ID_COMPRADOR' => 'Cb16  Id  Comprador',
            'CB16_GATEWAY' => 'Cb16  Gateway',
            'CB16_ID_FORMA_PAG_EMPRESA' => 'Cb16  Id  Forma  Pag  Empresa',
            'CB16_VALOR' => 'Cb16  Valor',
            'CB16_FRETE' => 'Cb16  Frete',
            'CB16_NUM_PARCELA' => 'Cb16  Num  Parcela',
            'CB16_STATUS' => 'Cb16  Status',
            'CB16_DT' => 'Cb16  Dt',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16IDCOMPRADOR()
    {
        return $this->hasOne(\common\models\CB02CLIENTE::className(), ['CB02_ID' => 'CB16_ID_COMPRADOR']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16USER()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'CB16_USER_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16IDFORMAPAGEMPRESA()
    {
        return $this->hasOne(\common\models\CB09FORMAPAGTOEMPRESA::className(), ['CB09_ID' => 'CB16_ID_FORMA_PAG_EMPRESA']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB17PRODUTOPEDIDOs()
    {
        return $this->hasMany(\common\models\CB17PRODUTOPEDIDO::className(), ['CB17_PEDIDO_ID' => 'CB16_ID']);
    }
    

}
