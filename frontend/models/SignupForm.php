<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;
use common\models\AuthAssignment;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $cpf_or_cnpj;
    
    public $username;
    public $name;
    public $cpf_cnpj;
    public $email;
    public $password;
    public $id_indicacao = null;
    
    public $item_name = 'cliente';

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['cpf_or_cnpj', 'safe'],
            
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 5, 'max' => 255],

            ['cpf_cnpj', 'trim'],
            ['cpf_cnpj', 'required'],
            ['cpf_cnpj', 'filter', 'filter' => function($value) {
                return preg_replace('/[^0-9]/', '', $value);
            }],
            ['cpf_cnpj', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este CPF/CNPJ já foi usado.'],
            ['cpf_cnpj', 'string', 'min' => 11, 'max' => 14],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este Email já foi usado.'],
            
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => 'Insira uma senha com no mínimo 6 dígitos.'],
            
            ['id_indicacao', 'integer'],
            ['id_indicacao', 'exist', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id', 'message' => 'A indicação não é valida.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
            
            $user = new User();
            $user->cpf_cnpj = $this->cpf_cnpj;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->username = $this->cpf_cnpj;
            $user->id_indicacao = $this->id_indicacao;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
            
            $assignment = new AuthAssignment();
            $assignment->item_name = $this->item_name;
            $assignment->user_id = (string) $user->id;
            $assignment->save();
            
            $transaction->commit();
            return $user;
    
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
    }

}
