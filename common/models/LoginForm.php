<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $cpf_cnpj;
    public $password;
    public $rememberMe = true;
    public $isCompanyLogin;

    private $_user;
    private $_cpf_cnpj;
    
    const SCENARIO_COMPANY_LOGIN = 'SCENARIO_COMPANY_LOGIN';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // cpf_cnpj and password are both required
            [['cpf_cnpj', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['cpf_cnpj', 'isUserCompany' , 'on' => self::SCENARIO_COMPANY_LOGIN],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'rememberMe' => 'Lembrar-me',        
        ];
    }
    
    public function isUserCompany($attribute, $params) 
    {   
        if (strlen($this->cpf_cnpj) < 8 ) {
                $this->addError($attribute, 'Erro ao tentar logar. O usuário não tem permissões para acessar essa área');
        }
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getCpfCnpj();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuário ou senha incorretos.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Logs in a user using the provided cpf and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function loginCpfCnpj()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getCpfCnpj(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Finds user by [[cpf_cnpj]]
     *
     * @return Cpf|null
     */
    protected function getCpfCnpj()
    {
        if ($this->_cpf_cnpj === null) {
            $this->_cpf_cnpj = User::findByCpfCnpj($this->cpf_cnpj);
        }

        return $this->_cpf_cnpj;
    }
}
