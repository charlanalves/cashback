<?php

namespace common\models;

use Yii;
use common\models\base\User as BaseUser;
use common\models\CB04EMPRESA;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser implements IdentityInterface
{
	
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    const PERFIL_ADMINISTRADOR = 'administrador';
    const PERFIL_CLIENTE = 'cliente';
    const PERFIL_ESTABELECIMENTO = 'estabelecimento';
    const PERFIL_FUNCIONARIO = 'funcionario';
    const PERFIL_REPRESENTANTE = 'representante';
    
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::PERFIL_CLIENTE] = ['email', 'password', 'cpf_cnpj', 'username', 'id_company', 'name'];
        $scenarios[self::PERFIL_ESTABELECIMENTO] = ['email', 'password', 'cpf_cnpj', 'username', 'id_company', 'name'];
        $scenarios[self::PERFIL_FUNCIONARIO] = ['email', 'password', 'cpf_cnpj', 'username', 'id_company', 'name'];
        $scenarios[self::PERFIL_REPRESENTANTE] = ['email', 'password', 'cpf_cnpj', 'username', 'id_company', 'name'];
        return $scenarios;
    }
    
    public function scenariosToPerfil($scenario)
    {
        $a = [
            'SCENARIOCLIENTE' => self::PERFIL_CLIENTE,
            'SCENARIOADMINISTRADOR' => self::PERFIL_ADMINISTRADOR,
            'SCENARIOESTABELECIMENTO' => self::PERFIL_ESTABELECIMENTO,
            'SCENARIOFUNCIONARIO' => self::PERFIL_FUNCIONARIO,
            'SCENARIOCOMISSAO' => [self::PERFIL_FUNCIONARIO, self::PERFIL_REPRESENTANTE],
        ];
        if (empty($a[$scenario])) {
            exit('DEV - É necessário definir o scenario: $model->setScenario(\common\models\LoginForm::SCENARIO) Scenarios diponíveis:' . implode(' | ', array_keys($a)));
        } else {
            return $a[$scenario];
        }
    }
    
    public static function getPerfil($id)
    {
        $sql = "SELECT item_name FROM auth_assignment WHERE user_id = :attr";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':attr', $id);
        $user = $command->queryOne();
        return $user['item_name'];
    }

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
            ['name', 'required', 'message' => 'O'],
            ['name', 'string', 'min' => 5, 'max' => 255],

            ['id_indicacao', 'integer'],
            ['id_indicacao', 'exist', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id', 'message' => 'A indicação não é valida.'],
            
            ['cpf_cnpj', 'trim'],
            ['cpf_cnpj', 'string', 'min' => 11, 'max' => 14],
            ['cpf_cnpj', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este CPF/CNPJ já foi usado.', 'on' => self::SCENARIO_DEFAULT],
                    
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email','default', 'value' => null],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este e-mail já foi usado.', 'on' => self::SCENARIO_DEFAULT],
            
            // validar email e cpf unico para o PERFIL_CLIENTE 
            [['email', 'cpf_cnpj'], 'validateClienteUnique', 'on' => self::PERFIL_CLIENTE],
            
            // validar email e cpf unico para o PERFIL_ESTABELECIMENTO
            [['email', 'cpf_cnpj'], 'validateEstabelecimentoUnique', 'on' => self::PERFIL_ESTABELECIMENTO],
            
            // validar email e cpf unico para o PERFIL_FUNCIONARIO
            [['email', 'cpf_cnpj'], 'validateFuncionarioUnique', 'on' => self::PERFIL_FUNCIONARIO],
            
            // validar email e cpf unico para o PERFIL_REPRESENTANTE
            [['email', 'cpf_cnpj'], 'validateRepresentanteUnique', 'on' => self::PERFIL_REPRESENTANTE],
        ];
    }
    
    /**
     * VALIDATE - existe usuario com attr unico por perfil
     */
    private function validateExistUserPerfil($attribute, $perfil)
    {
        $union = "";
        // valida cpf do funcionario para a mesma empresa
        // permite criar funcionarios com o mesmo cpf/email para diferentes empresas
        if ($perfil === self::PERFIL_FUNCIONARIO) {
            $union = "UNION SELECT CB04_ID AS id 
                      FROM VIEW_FUNCIONARIO 
                      WHERE CB04_CNPJ = '" . $this->cpf_cnpj . "' AND 
                            CB04_ID <> " . $this->id_company . " AND 
                            CB04_ID_EMPRESA = (SELECT CB04_ID_EMPRESA FROM VIEW_FUNCIONARIO WHERE CB04_ID <> " . $this->id_company . ")";
        }
        
        $sql = "SELECT user.id AS id
                FROM user
                INNER JOIN auth_assignment on (id = user_id AND item_name = :perfil)
                WHERE $attribute = :attr AND user.id_company <> :id_company $union";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':attr', $this->$attribute);
        $command->bindValue(':perfil', $perfil);
        $command->bindValue(':id_company', $this->id_company);
                
        if ($command->queryOne()) {
            $this->addError($attribute, 'O ' . $this->getAttributeLabel($attribute) . ' informado já foi cadastrado.');
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * VALIDATE - validateClienteUnique
     */
    public function validateClienteUnique($attribute)
    {
        return !($this->validateExistUserPerfil($attribute, self::PERFIL_CLIENTE));
    }
    
    /**
     * VALIDATE - validateEstabelecimentoUnique
     */
    public function validateEstabelecimentoUnique($attribute)
    {
        return !($this->validateExistUserPerfil($attribute, self::PERFIL_ESTABELECIMENTO));
    }
    
    /**
     * VALIDATE - validateFuncionarioUnique
     */
    public function validateFuncionarioUnique($attribute)
    {
        return !($this->validateExistUserPerfil($attribute, self::PERFIL_FUNCIONARIO));
    }
    
    /**
     * VALIDATE - validateRepresentanteUnique
     */
    public function validateRepresentanteUnique($attribute)
    {
        return !($this->validateExistUserPerfil($attribute, self::PERFIL_REPRESENTANTE));
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
    public static function findByCpfCnpj($cpf_cnpj, $perfil = null)
    {
        $statusAtivo = self::STATUS_ACTIVE;
        if ($perfil) {
            $perfil = is_array($perfil) ? "'" . implode("','", $perfil) . "'" : "'$perfil'";
            $sql = "SELECT user.*
                    FROM user
                    INNER JOIN auth_assignment on (id = user_id AND item_name IN($perfil))
                    WHERE user.cpf_cnpj = :cpf_cnpj AND user.status = :status";
            $command = \Yii::$app->db->createCommand($sql);
            $command->bindParam(':cpf_cnpj', $cpf_cnpj);
            $command->bindParam(':status', $statusAtivo);
            if (($user = $command->queryOne())) {
                $user = static::findOne($user['id']);
            }
        } else {
            $user = static::findOne(['cpf_cnpj' => $cpf_cnpj, 'status' => $statusAtivo]);
        }
        
        if (!empty($user->id_company)) {
            // busca apenas quem nao e empresa - para o id_company ficar correto
            $id_company = CB04EMPRESA::find()->where("CB04_ID = " . $user->id_company . " AND CB04_TIPO <> 1")->one();
            if (!empty($id_company->CB04_ID_EMPRESA)) {
                    $user->setAttribute('id_company', $id_company->CB04_ID_EMPRESA);
            }
        }
        return $user;
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
    
    // o usuario do estabelecimento com o perfil "estabelecimento" e o pricipal
    public static function getCompanyUserMainId($company)
    {
        $sql = "SELECT user.*
                FROM user
                INNER JOIN auth_assignment on (id = user_id AND item_name = :perfil)
                WHERE id_company = :company";

        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':company', $company);
        $command->bindValue(':perfil', self::PERFIL_ESTABELECIMENTO);
        return $command->queryOne();
    }
    
    public static function getFuncionarios($company)
    {
        $sql = "SELECT user.*
                FROM user
                INNER JOIN auth_assignment on (id = user_id AND item_name = :perfil)
                WHERE id_company = :company";

        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':company', $company);
        $command->bindValue(':perfil', self::PERFIL_FUNCIONARIO);
        return $command->queryAll();
    }

}
