<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB02_CLIENTE".
 *
 * @property integer $CB02_ID
 * @property string $CB02_NOME
 * @property string $CB02_CPF
 * @property string $CB02_EMAIL
 * @property string $CB02_DADOS_API_TOKEN
 * @property integer $CB02_STATUS
 * @property string $CB02_DT_CADASTRO
 *
 * @property common\models\CB16PEDIDO[] $cB16PEDIDOs
 * @property common\models\User[] $users
 */
class CB02CLIENTE extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB02_NOME', 'CB02_CPF', 'CB02_EMAIL', 'CB02_DADOS_API_TOKEN'], 'required'],
            [['CB02_DADOS_API_TOKEN'], 'string'],
            [['CB02_STATUS'], 'integer'],
            [['CB02_DT_CADASTRO'], 'safe'],
            [['CB02_NOME', 'CB02_EMAIL'], 'string', 'max' => 50],
            [['CB02_CPF'], 'string', 'max' => 14],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB02_CLIENTE';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB02_ID' => 'Cb02  ID',
            'CB02_NOME' => 'Cb02  Nome',
            'CB02_CPF' => 'Cb02  Cpf',
            'CB02_EMAIL' => 'Cb02  Email',
            'CB02_DADOS_API_TOKEN' => 'Cb02  Dados  Api  Token',
            'CB02_STATUS' => 'Cb02  Status',
            'CB02_DT_CADASTRO' => 'Cb02  Dt  Cadastro',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16PEDIDOs()
    {
        return $this->hasMany(\common\models\CB16PEDIDO::className(), ['CB16_ID_COMPRADOR' => 'CB02_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['id_cliente' => 'CB02_ID']);
    }
    

}
