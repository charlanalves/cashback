<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends User
{
 public $id;
    public $username;
    public $cpf_cnpj;
    public $password;
    public $rememberMe = true;
    public $isCompanyLogin;

    private $_user;
    private $_cpf_cnpj;
    
    const SCENARIO_COMPANY_LOGIN = 'SCENARIO_COMPANY_LOGIN';
    
    const SCENARIOADMINISTRADOR = 'SCENARIOADMINISTRADOR';
    const SCENARIOESTABELECIMENTO = 'SCENARIOESTABELECIMENTO';
    const SCENARIOFUNCIONARIO = 'SCENARIOFUNCIONARIO';
    const SCENARIOCOMISSAO= 'SCENARIOCOMISSAO';
    const SCENARIOVALIDAREMAIL = 'SCENARIOVALIDAREMAIL';
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIOADMINISTRADOR] = ['username', 'password', 'rememberMe', 'id'];
        $scenarios[self::SCENARIOESTABELECIMENTO] = ['cpf_cnpj', 'password', 'rememberMe', 'id'];
        $scenarios[self::SCENARIOFUNCIONARIO] = ['cpf_cnpj', 'password', 'rememberMe', 'id'];
        $scenarios[self::SCENARIOCOMISSAO] = ['cpf_cnpj', 'password', 'rememberMe', 'id'];
        $scenarios[self::SCENARIO_COMPANY_LOGIN] = ['cpf_cnpj', 'password', 'rememberMe', 'id'];
        $scenarios[self::SCENARIOVALIDAREMAIL] = ['email_valid'];
        return $scenarios;
    }
    
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
            
            ['email_valid', 'safe'],

            // validar estabelecimento
            //['cpf_cnpj', 'string', 'length' => 14, 'message' => 'Informe um CNPJ válido.', 'on' => self::SCENARIOESTABELECIMENTO],
            ['id', 'filter', 'filter' => function ($idUser) {
                if(!$idUser){
                } else if (!AuthAssignment::find()->where("user_id = $idUser AND item_name IN('estabelecimento','funcionario')")->one()) {
                    $this->addError('cpf_cnpj', '');
                    $this->addError('password', 'Seu usuário não tem permissão de acesso, entre em contato com o administrador do sistema.');
                } else {
                    return true;
                }
            }, 'on' => self::SCENARIOESTABELECIMENTO],

            // validar app funcionario
            ['id', 'filter', 'filter' => function ($idUser) {
                if(!$idUser){
                } else if (!AuthAssignment::find()->where("user_id = $idUser AND item_name IN('funcionario')")->one()) {
                    $this->addError('cpf_cnpj', '');
                    $this->addError('password', 'Seu usuário não tem permissão de acesso, entre em contato com o administrador do sistema.');
                } else {
                    return true;
                }
            }, 'on' => self::SCENARIOFUNCIONARIO],
                    
            // validar app comissao
            ['id', 'filter', 'filter' => function ($idUser) {
                if(!$idUser){
                } else if (!AuthAssignment::find()->where("user_id = $idUser AND item_name IN('funcionario', 'representante')")->one()) {
                    $this->addError('cpf_cnpj', '');
                    $this->addError('password', 'Seu usuário não tem permissão de acesso, entre em contato com o administrador do sistema.');
                } else {
                    return true;
                }
            }, 'on' => self::SCENARIOCOMISSAO],
                    
            // validar administrador
            ['username', 'required', 'on' => self::SCENARIOADMINISTRADOR],
            ['id', 'filter', 'filter' => function ($idUser) {
                if (!AuthAssignment::findOne(['user_id' => $idUser, 'item_name' => 'administrador'])) {
                    $this->addError('username', '');
                    $this->addError('password', 'O usuário não tem permissões de acesso.');
                } else {
                    return true;
                }
            }, 'on' => self::SCENARIOADMINISTRADOR],

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
        $userData = $this->getUserByCpfCnpj();
        
        if ( !empty($userData) ){
            $userType = $userData->getAttributes()['user_type'];
            if ($userType != 2) {
                    $this->addError($attribute, 'Erro ao tentar logar. O usuário não tem permissões para acessar essa área');
            }
        } 
    }
    
    public function getUserByCpfCnpj(){
        return User::findByCpfCnpj($this->cpf_cnpj, $this->getScenario());
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
            
            if($this->cpf_cnpj){
                $user = $this->getCpfCnpj();
            } else if($this->username){
                $user = $this->getUser();
            }
            
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Dados incorretos.');
            } else {
                $this->id = $user->id;
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
