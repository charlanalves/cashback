<?php

namespace common\models\base;

use Yii;






/**
 * This is the base model class for table "user".
 *
 * @property integer $id
 * @property integer $id_company
 * @property integer $id_cliente
 * @property string $name
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $cpf_cnpj
 * @property integer $id_indicacao
 *
 * @property common\models\CB00TRANSFERENCIA[] $cB00TRANSFERENCIAs
 * @property common\models\CB01TRANSACAO[] $cB01TRANSACAOs
 * @property common\models\CB03CONTABANC[] $cB03CONTABANCs
 * @property common\models\CB15LIKEEMPRESA[] $cB15LIKEEMPRESAs
 * @property common\models\CB04EMPRESA[] $cB15EMPRESAs
 * @property common\models\CB16PEDIDO[] $cB16PEDIDOs
 * @property common\models\PAG04TRANSFERENCIAS[] $pAG04TRANSFERENCIASs
 * @property common\models\CB02CLIENTE $cliente
 * @property common\models\User $indicacao
 * @property common\models\User[] $users
 * @property common\models\CB04EMPRESA $company
 */
class User extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_company', 'id_cliente', 'status', 'created_at', 'updated_at', 'id_indicacao'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'cpf_cnpj'], 'required', 'message' => 'O campo <b>{attribute}</b> é obrigatório'],
            [['name'], 'string', 'max' => 200],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['cpf_cnpj'], 'string', 'max' => 14],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['cpf_cnpj'], 'unique'],
            [['password_reset_token'], 'unique'],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_company' => 'Id Company',
            'id_cliente' => 'Id Cliente',
            'name' => 'Name',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'cpf_cnpj' => 'CPF/CNPJ',
            'id_indicacao' => 'Id Indicacao',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB00TRANSFERENCIAs()
    {
        return $this->hasMany(\common\models\CB00TRANSFERENCIA::className(), ['CB00_CLIENTE_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01TRANSACAOs()
    {
        return $this->hasMany(\common\models\CB01TRANSACAO::className(), ['CB01_CLIENTE_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB03CONTABANCs()
    {
        return $this->hasMany(\common\models\CB03CONTABANC::className(), ['CB03_CLIENTE_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15LIKEEMPRESAs()
    {
        return $this->hasMany(\common\models\CB15LIKEEMPRESA::className(), ['CB15_USER_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB15EMPRESAs()
    {
        return $this->hasMany(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'CB15_EMPRESA_ID'])->viaTable('CB15_LIKE_EMPRESA', ['CB15_USER_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB16PEDIDOs()
    {
        return $this->hasMany(\common\models\CB16PEDIDO::className(), ['CB16_USER_ID' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPAG04TRANSFERENCIASs()
    {
        return $this->hasMany(\common\models\PAG04TRANSFERENCIAS::className(), ['PAG04_ID_USER' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(\common\models\CB02CLIENTE::className(), ['CB02_ID' => 'id_cliente']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndicacao()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'id_indicacao']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['id_indicacao' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(\common\models\CB04EMPRESA::className(), ['CB04_ID' => 'id_company']);
    }
    


   
}
