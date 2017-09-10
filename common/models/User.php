<?php

namespace common\models;

use Yii;
use common\models\base\User as BaseUser;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser implements IdentityInterface
{
	
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    /**
     * @inheritdoc
     */
 	public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['cpf_or_cnpj', 'email_valid'], 'safe'],
            
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 5, 'max' => 255],

            ['cpf_cnpj', 'trim'],
            ['cpf_cnpj', 'required', 'message' => 'O campo <b>{attribute}</b> é obrigatório'],
            ['cpf_cnpj', 'filter', 'filter' => function($value) {
                return preg_replace('/[^0-9]/', '', $value);
            }],
            ['cpf_cnpj', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este CPF/CNPJ já foi usado.'],
            ['cpf_cnpj', 'string', 'min' => 11, 'max' => 14],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este e-mail já foi usado.'],
            
           
            
            ['id_indicacao', 'integer'],
            ['id_indicacao', 'exist', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id', 'message' => 'A indicação não é valida.'],
        ];
    }
    
 /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by cpf_cnpj
     *
     * @param string $cpf_cnpj
     * @return static|null
     */
    public static function findByCpfCnpj($cpf_cnpj)
    {
        return static::findOne(['cpf_cnpj' => $cpf_cnpj, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id_company' => 'id_company']);
    }
    
    public static function getIdByAuthKey($authKey)
    {
        return (($user = self::findOne(['auth_key' => $authKey]))) ? $user->id : false;
    }
    
    public static function getHashPasswordByAuthKey($authKey)
    {
        return (($user = self::findOne(['auth_key' => $authKey]))) ? $user->password_hash : false;
    }
    
    public static function getCompanyUserMainId($company)
    {
        return (($user = self::findOne(['id_company' => $company, 'user_principal' => 1]))) ? $user->id : false;
    }

}
