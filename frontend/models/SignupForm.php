<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $cpf_cnpj;
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            ['username', 'trim'],
//            ['username', 'required'],
//            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este Username já foi usado.'],
//            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['cpf_cnpj', 'trim'],
            ['cpf_cnpj', 'required'],
            ['cpf_cnpj', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este CPF/CNPJ já foi usado.'],
            ['cpf_cnpj', 'string', 'min' => 11, 'max' => 14],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este Email já foi usado.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => 'Insira uma senha com no mínimo 6 dígitos.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->cpf_cnpj = $this->cpf_cnpj;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->username = $this->cpf_cnpj;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
