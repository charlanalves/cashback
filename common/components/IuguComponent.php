<?php

namespace common\components;

use \yii\base\UserException;

/**
 * PaymentBaseComponent
 * 
 * */
class IuguComponent extends PaymentBaseComponent
{

    const tbCliente = '\common\models\user';
    const tbEmpresa = '\common\models\CB04EMPRESA';
    const prefixCliente = '';
    const prefixEmpresa = 'CB04_';
    const STATUS_PAID = 'paid';
    const apiTokenProd = '19f75e24d08d0dd3d01db446299a4ba6';
    const apiTokenTest = '67dfbb3560a62cb5cee9ca8730737a98';
    const status_request_accepted = 'accepted';

    protected function initialize()
    {
        require_once(\Yii::getAlias('@vendor/iugu/Iugu.php'));

        //teste
        //\Iugu::setApiKey(self::apiTokenTest);
        //producao
        \Iugu::setApiKey(self::apiTokenProd);
    }

    protected function prepareCreditCard($data)
    {
        
    }

    protected function processTransaction($data)
    {
        $this->lastResponse = \Iugu_Charge::create($data);
        $msg = '';
        if (isset($this->lastResponse->errors)) {
            if (is_array($this->lastResponse->errors)) {
                foreach ($this->lastResponse->errors as $k => $e) {
                    if (is_array($this->lastResponse->errors[$k])) {
                        foreach ($this->lastResponse->errors[$k] as $a) {
                            $msg .= $a . ' ';
                        }
                    } else {
                        $msg .= $this->lastResponse->errors[$k] . ' ';
                    }
                }
            } else {
                $msg = $this->lastResponse->errors;
            }

            if (empty($msg)) {
                $msg = "O pagamento não foi autorizado tente novamente com outro cartão de crédito.";
            }
            throw new UserException($msg);
        } else if (!$this->lastResponse->success) {
            $msg = $this->lastResponse->message . ' Vefique os dados do cartão ou tente novamente com outro cartão de crédito.';
            throw new UserException($this->lastResponse->message);
        }
    }

    private function saveApiCod($model)
    {
        $model->CB04_DADOS_API_TOKEN = json_encode($this->lastResponse);
        $model->CB04_COD_CONTA_VIRTUAL = $this->lastResponse->account_id;
        $model->save();
    }

    private function saveCompanyLogo($model, $id)
    {
        if (!empty($_FILES['CB04_URL_LOGOMARCA']['name'])) {

            $infoFile = \Yii::$app->u->infoFile($_FILES['CB04_URL_LOGOMARCA']);
            if ($infoFile['family'] == 'image') {
                $infoFile['path'] = 'img/fotos/estabelecimento/';
                $infoFile['newName'] = uniqid("logo_" . $id . "_") . '.' . $infoFile['ex'];

                $file = \yii\web\UploadedFile::getInstanceByName('CB04_URL_LOGOMARCA');
                $pathCompleto = $infoFile['path'] . $infoFile['newName'];

                if ($file->saveAs($pathCompleto)) {
                    if (!empty($model->CB04_URL_LOGOMARCA)) {
                        @unlink($model->CB04_URL_LOGOMARCA);
                    }
                    $model->setAttribute('CB04_URL_LOGOMARCA', $pathCompleto);
                    $model->save();
                }
            }
        }
    }

    /**
     * Cria usuario para o estabelecimento
     * o primeiro e criado como principal, responsavel pela gestao dos dados do 
     * estabelecimento e fica com o perfil "estabelecimento"
     * o seguntes sao funcionario
     * @param int/instance $empresa ( id ou instancia da empresa (CB04_EMPRESA))
     * @return void
     */
    private function createCompanyUser($empresa)
    {

        // valida empresa
        if (!($empresa = is_int($empresa) ? \common\models\CB04EMPRESA::findOne($empresa) : $empresa)) {
            throw new UserException("Erro ao tentar criar o usuário, empresa não encontrada.");
        }

        // verifica se existe usuario, se existe ele cria usuario com perfil funcionario
        $userEstabelecimento = \common\models\User::getCompanyUserMainId($empresa->CB04_ID);
        $qtdFuncionarios = $userEstabelecimento ? count(\common\models\User::getFuncionarios($empresa->CB04_ID)) + 1 : 0;

        $user = new \common\models\User;

        if (!$qtdFuncionarios) {
            // estabelecimento
            $perfil = \common\models\User::PERFIL_ESTABELECIMENTO;
            $user->email = $empresa->CB04_EMAIL;
            $user->cpf_cnpj = $empresa->CB04_CNPJ;
            $user->username = $empresa->CB04_CNPJ;
        } else {
            // funcionario
            $perfil = \common\models\User::PERFIL_FUNCIONARIO;
            $user->cpf_cnpj = $empresa->CB04_CNPJ . "-" . $qtdFuncionarios;
            $user->username = $empresa->CB04_CNPJ . "-" . $qtdFuncionarios;
        }

        $user->id_company = $empresa->CB04_ID;
        $user->name = $empresa->CB04_NOME;
//        $user->user_principal = 1;
        $user->setPassword(123456);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = $perfil;
        $assignment->user_id = (string) $user->id;
        $assignment->save();
    }

    public function createCompanyAccount($dataApi)
    {
        $data = $dataApi['data'];
        $model = $dataApi['model'];
        $id = $dataApi['id'];

        $this->_createAccount($data['cnpj']);
        $this->verifyAccount($data);
        $this->saveApiCod($model);
        $this->saveCompanyLogo($model, $id);

        //Cria o usuário do admin do estabelecimento
        $this->createCompanyUser($model);

        //Cria o usuário do funcionario
        $this->createCompanyUser($model);
    }

    public function createAccount($accountName)
    {
        $this->_createAccount($accountName);
    }

    public function _createAccount($accountName)
    {
        if (empty($accountName)) {
            throw new UserException("Erro ao criar conta. CNPJ ou CPF não informado.");
        }

        $this->lastResponse = \Iugu_Marketplace::createAccount(['name' => $accountName]);

        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao criar conta.");
        }
    }

    public function verifyAccount($dataApi)
    {
        $defaultData = [
            "price_range" => "Mais que R$ 500,00",
            "physical_products" => false,
            "automatic_transfer" => true,
        ];

        if (isset($dataApi['cnpj'])) {
            $defaultPJ = [
                "person_type" => 'Pessoa Jurídica',
                "business_type" => "Serviços e produtos diversos",
            ];

            $dataApi = array_merge(array_merge($defaultPJ, $defaultData), $dataApi);
        } else {
            $defaultPF = [
                "business_type" => "Usuário com cashback",
                "person_type" => "Pessoa Física",
                "cpf" => $dataApi['CPF_CNPJ'],
            ];

            $dataApi = array_merge(array_merge($defaultPF, $defaultData), $dataApi);
        }

        \Iugu::setApiKey($this->lastResponse->user_token);
        $data['data'] = $dataApi;
        $this->lastResponse = \Iugu_Account::requestVerification($data, $this->lastResponse->account_id);


        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao Verificar conta");
        }
    }

    private function prepareClientAccountData($param)
    {
        return [
            "data" =>
            [
                "price_range" => "Mais que R$ 500,00",
                "physical_products" => false,
                "business_type" => "Cliente CashBack",
                "automatic_transfer" => true,
                "person_type" => 'Pessoa Física',
                "cpf" => $param['CB02_CPF_CNPJ'],
                "name" => $param['CB02_NOME'],
                "address" => 'Rua inexisteste',
                "cep" => '31650-000',
                "city" => 'BH',
                "state" => 'MG',
                "telephone" => '31999999999',
                "bank" => $param['CB03_NOME_BANCO'],
                "bank_ag" => $param['CB03_AGENCIA'],
                "account_type" => ($param['CB03_TP_CONTA']) ? 'Corrente' : 'Poupança',
                "bank_cc" => $param['CB03_NUM_CONTA']
            ]
        ];
    }

    public function verifyClientAccount($param)
    {
        try {

            $token = json_decode($param['CB02_DADOS_API_TOKEN']);
            \Iugu::setApiKey($token->user_token);
            $this->lastResponse = \Iugu_Account::fetch($token->account_id);

            if (isset($this->lastResponse->last_verification_request_status)) {

                if ($this->lastResponse->last_verification_request_status == self::status_request_accepted) {

                    $this->doTranfer($param['PAG04_VLR'], $token->account_id);

                    $trans = \common\models\PAG04TRANSFERENCIAS::findOne($param['PAG04_ID']);
                    // $trans->PAG04_DT_DEP = date('Y-m-d H:i:s');
                    $trans->PAG04_STATUS = 1;
                    $trans->save();
                } else {
                    $this->lastResponse = \Iugu_Account::requestVerification($param, $token->account_id);

                    if (isset($this->lastResponse->errors)) {
                        throw new UserException("Erro ao Verificar a conta.");
                    }
                }
            }
        } catch (\Exception $ex) {
            
        }
    }

    public function doTranfer($amount, $accountId)
    {

        \Iugu::setApiKey(self::apiTokenProd);

        $data['receiver_id'] = $accountId;
        $data['amount_cents'] = $amount * 100;

        $this->lastResponse = \Iugu_Transfer::create($data);
        $msg = '';
        if (isset($this->lastResponse->errors)) {
            if (is_array($this->lastResponse->errors)) {
                foreach ($this->lastResponse->errors as $key => $value) {
                    if (is_array($this->lastResponse->errors[$key])) {
                        foreach ($this->lastResponse->errors[$key] as $v2) {
                            $msg .= $v2 . " <br> ";
                        }
                    }
                }
            }
            throw new \Exception($msg);
        }
    }

    public function fetchAccount()
    {
        $this->lastResponse = \Iugu_Account::fetch('6C65DFAABC5648B58D0E9D854EB52E04');

        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao criar conta.");
        }
    }

    public function AccountConfig()
    {
        $this->lastResponse = \Iugu_Account::configuration(['auto_withdraw' => true]);

        if (isset($this->lastResponse->errors)) {
            throw new UserException("Erro ao criar conta.");
        }
    }

    protected function createSaveClienteAccount($atributos)
    {
        $this->transaction = \Yii::$app->db->beginTransaction();

        $cliente = new \common\models\CB02CLIENTE;
        $cliente->setAttributes($atributos);
        $cliente->save();

        $user = new \common\models\User;
        $user->cpf_cnpj = $cliente->CB02_CPF_CNPJ;
        $user->name = $cliente->CB02_NOME;
        $user->id_cliente = $cliente->CB02_ID;
        $user->email = $cliente->CB02_EMAIL;
        $user->username = $cliente->CB02_CPF_CNPJ;

        // logica da indicação do amigo
        if (!empty($atributos['auth_key'])) {
            $user->auth_key = $atributos['auth_key'];
            $friend = \common\models\User::findBySql('SELECT * FROM user where auth_key  = "' . $atributos['auth_key'] . '"')->one();
            if (!empty($friend->id)) {
                $user->id_indicacao = $friend->id;
            }
        }

        if (!empty($atributos['cod_indicacao'])) {
            $user->id_indicacao = (\common\models\User::getIdByAuthKey($atributos['cod_indicacao'])) ? : null;
        }
        $user->setPassword($atributos['password']);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = 'cliente';
        $assignment->user_id = (string) $user->id;
        $assignment->save();

        $this->createAccount($cliente->CB02_CPF_CNPJ);

        $cliente->CB02_DADOS_API_TOKEN = json_encode($this->lastResponse);
        $cliente->CB02_COD_CONTA_VIRTUAL = $this->lastResponse->account_id;

        $cliente->save();

        $model = new \common\models\LoginForm();
        $model->cpf_cnpj = $cliente->CB02_CPF_CNPJ;
        $model->password = $atributos['password'];
        $model->loginCpfCnpj();

        \Yii::$app->sendMail->enviarEmailCadastro($atributos['CB02_EMAIL'], $user->auth_key);

        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }

    
    private function prepareRepresentanteAccountData($param)
    {
        return [
            "data" =>
            [
                "price_range" => "Mais que R$ 500,00",
                "physical_products" => false,
                "business_type" => "Comissão Representante",
                "automatic_transfer" => true,
                "person_type" => 'Pessoa Física',
                "cpf" => $param['CB04_CNPJ'],
                "name" => $param['CB04_NOME'],
                "address" => 'Rua inexisteste',
                "cep" => '31650-000',
                "city" => 'BH',
                "state" => 'MG',
                "telephone" => '31999999999',
                "bank" => $param['CB03_NOME_BANCO'],
                "bank_ag" => $param['CB03_AGENCIA'],
                "account_type" => ($param['CB03_TP_CONTA']) ? 'Corrente' : 'Poupança',
                "bank_cc" => $param['CB03_NUM_CONTA']
            ]
        ];
    }

    private function verifyRepresentanteAccount($CB04_DADOS_API_TOKEN, $param)
    {
        try {
            $token = json_decode($CB04_DADOS_API_TOKEN);
            \Iugu::setApiKey($token->user_token);
            $this->lastResponse = \Iugu_Account::fetch($token->account_id);
            if (isset($this->lastResponse->last_verification_request_status)) {
                $this->lastResponse = \Iugu_Account::requestVerification($param, $token->account_id);
                if (isset($this->lastResponse->errors)) {
                    throw new UserException("Erro ao Verificar a conta.");
                }
            }
        } catch (\Exception $ex) {
            throw new UserException($ex->getMessage());
        }
    }
    
    private function createRepresentanteUser($representante)
    {

        // valida representante
        if (!($representante = is_int($representante) ? \common\models\VIEWREPRESENTANTE::findOne($representante) : $representante)) {
            throw new UserException("Erro ao tentar criar o usuário, representante não encontrado.");
        }

        $user = new \common\models\User;
        $user->email = $representante->CB04_EMAIL;
        $user->cpf_cnpj = $representante->CB04_CNPJ;
        $user->username = $representante->CB04_CNPJ;
        $user->id_company = $representante->CB04_ID;
        $user->name = $representante->CB04_NOME;
        $user->setPassword(123456);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = \common\models\User::PERFIL_REPRESENTANTE;
        $assignment->user_id = (string) $user->id;
        $assignment->save();
    }

    protected function createRepresentanteAccount($dataApi)
    {
        $data = $dataApi['data'];
        $this->createAccount($data['CB04_CNPJ']);
        $model = \common\models\VIEWREPRESENTANTE::findOne($dataApi['id']);
        $this->saveApiCod($model);
        $this->verifyRepresentanteAccount($model['CB04_DADOS_API_TOKEN'], $this->prepareRepresentanteAccountData($data));
        $this->createRepresentanteUser($model);

        \Yii::$app->sendMail->enviarEmailCreateRevendedor($data['CB04_EMAIL'], [
            'nome' => $data['CB04_NOME'],
            'cnpj' => $data['CB04_CNPJ'],
            'email' => $data['CB04_EMAIL'],
            'telefone' => $data['CB04_TEL_NUMERO'],
            'observacao' => $data['CB04_OBSERVACAO'],
            'endereco' => $data['CB04_END_LOGRADOURO'] . ', ' . $data['CB04_END_NUMERO'] . ', ' . $data['CB04_END_BAIRRO'] . ' - ' . $data['CB04_END_CIDADE'] . '/' . $data['CB04_END_UF'],
            'dados_banco' => $data['CB03_NOME_BANCO'] . ' - ' . ($data['CB03_TP_CONTA'] ? 'Corrente' : 'Poupança') . ' Ag.: ' . $data['CB03_AGENCIA'] . ' Conta:' . $data['CB03_NUM_CONTA']
        ]);

        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }

    
    private function prepareFuncionarioAccountData($param)
    {
        return [
            "data" =>
            [
                "price_range" => "Mais que R$ 500,00",
                "physical_products" => false,
                "business_type" => "Comissão Funcionário",
                "automatic_transfer" => true,
                "person_type" => 'Pessoa Física',
                "cpf" => $param['CB04_CNPJ'],
                "name" => $param['CB04_NOME'],
                "address" => 'Rua inexisteste',
                "cep" => '31650-000',
                "city" => 'BH',
                "state" => 'MG',
                "telephone" => '31999999999',
                "bank" => $param['CB03_NOME_BANCO'],
                "bank_ag" => $param['CB03_AGENCIA'],
                "account_type" => ($param['CB03_TP_CONTA']) ? 'Corrente' : 'Poupança',
                "bank_cc" => $param['CB03_NUM_CONTA']
            ]
        ];
    }

    private function verifyFuncionarioAccount($CB04_DADOS_API_TOKEN, $param)
    {
        return $this->verifyRepresentanteAccount($CB04_DADOS_API_TOKEN, $param);
    }
    
    private function createFuncionarioUser($funcionario)
    {

        // valida funcionario
        if (!($funcionario = is_int($funcionario) ? \common\models\VIEWFUNCIONARIO::findOne($funcionario) : $funcionario)) {
            throw new UserException("Erro ao tentar criar o usuário, funcionário não encontrado.");
        }

        $user = new \common\models\User;
        $user->email = $funcionario->CB04_EMAIL;
        $user->cpf_cnpj = $funcionario->CB04_CNPJ;
        $user->username = $funcionario->CB04_CNPJ;
        $user->id_company = $funcionario->CB04_ID;
        $user->name = $funcionario->CB04_NOME;
        $user->setPassword(123456);
        $user->generateAuthKey();
        $user->save();

        $assignment = new \common\models\AuthAssignment;
        $assignment->item_name = \common\models\User::PERFIL_FUNCIONARIO;
        $assignment->user_id = (string) $user->id;
        $assignment->save();
    }
    
    protected function createFuncionarioAccount($dataApi)
    {
        $data = $dataApi['data'];
        $this->createAccount($data['CB04_CNPJ']);
        $model = \common\models\VIEWFUNCIONARIO::findOne($dataApi['id']);
        $this->saveApiCod($model);
        $this->verifyFuncionarioAccount($model['CB04_DADOS_API_TOKEN'], $this->prepareFuncionarioAccountData($data));
        $this->createFuncionarioUser($model);

        \Yii::$app->sendMail->enviarEmailCreateFuncionario($data['CB04_EMAIL'], [
            'nome' => $data['CB04_NOME'],
            'cnpj' => $data['CB04_CNPJ'],
            'email' => $data['CB04_EMAIL'],
            'telefone' => $data['CB04_TEL_NUMERO'],
            'observacao' => $data['CB04_OBSERVACAO'],
            'endereco' => $data['CB04_END_LOGRADOURO'] . ', ' . $data['CB04_END_NUMERO'] . ', ' . $data['CB04_END_BAIRRO'] . ' - ' . $data['CB04_END_CIDADE'] . '/' . $data['CB04_END_UF'],
            'dados_banco' => $data['CB03_NOME_BANCO'] . ' - ' . ($data['CB03_TP_CONTA'] ? 'Corrente' : 'Poupança') . ' Ag.: ' . $data['CB03_AGENCIA'] . ' Conta:' . $data['CB03_NUM_CONTA']
        ]);

        return json_encode(($model->errors ? ['error' => $model->errors] : \Yii::$app->user->identity->attributes));
    }

    public function fetchUpdateDtDepInvoice(array $invoices)
    {
        foreach ($invoices as $key => $invoice) {

            $invoiceRet = \Iugu_Invoice::fetch($invoice['CB16_COD_TRANSACAO']);

            if (isset($invoiceRet->financial_return_dates)) {
                if (isset($invoiceRet->financial_return_dates[0]->status)) {
                    $pedido = \common\models\CB16PEDIDO::findOne($invoice['CB16_ID']);
                    $pedido->CB16_DT_DEP = date('Y-m-d H:i:s');
                    $pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_pago_trans_liberadas;
                    $pedido->save();
                }
            }
        }
    }

//    public function realizaSaques()
//    {
//
//        if (($saquesPendentes = \common\models\CB16PEDIDO::getSaquesPendentes())) {
//
//            $this->transaction = \Yii::$app->db->beginTransaction();
//
//            foreach ($saquesPendentes as $sp) {
//
//                $token = json_decode($sp['CB02_DADOS_API_TOKEN']);
//                $sp = $this->prepareClientAccountData($sp);
//                $this->verifyClientAccount($sp);
//            }
//        }
//    }
    
    public function realizaSaques($id = '') 
    {   
    	  try {
           
	    	  if (($saquesPendentes = \common\models\CB16PEDIDO::getSaquesPendentes($id))) {
	
	            $this->transaction = \Yii::$app->db->beginTransaction();
	            
	            foreach ($saquesPendentes as $sp) {
	
	                $token = json_decode($sp['CB02_DADOS_API_TOKEN']);
	                $this->verifyClientAccount($sp);
	            }
	        }
        } catch (\Exception $e) {
            $status = false;
            $message = $retorno = $dev = $e->getMessage(); 
        }
    }

    protected function prepareDebitCard($data)
    {
        
    }

    public function criaTransferenciaPagSaldo($pedido)
    {

        if (($pedidos = \common\models\CB16PEDIDO::getPedidoCompletoByPedido($pedido))) {

            $this->transaction = \Yii::$app->db->beginTransaction();

            foreach ($pedidos as $pedido) {

                $idCliente = $pedido['ID_USER_CLIENTE'];
                $idEmpresa = $pedido['ID_USER_EMPRESA'];
                $idPedido = $pedido['CB16_ID'];
                $vlrPedido = $pedido['CB16_VALOR'];
                $vlrCliente = floor($pedido['CB16_VLR_CB_TOTAL'] * 100) / 100;
                $vlrAdmin = floor((($pedido['CB16_PERC_ADMIN'] / 100) * $pedido['CB16_VALOR']) * 100) / 100;
                $vlrAdq = floor((($pedido['CB16_PERC_ADQ'] / 100) * $pedido['CB16_VALOR']) * 100) / 100;
                $dtPrevisao = $this->getDtPrevisao($pedido['CB08_PRAZO_DIAS_RECEBIMENTO'], $pedido['CB16_DT_APROVACAO']);

                $trans = new \common\models\PAG04TRANSFERENCIAS;

                // TRANSFÊNCIA CLIENTE TO EMPRESA
                $trans->createC2E($idCliente, $idEmpresa, $vlrPedido, $idPedido);

                // TRANSFÊNCIA MASTER TO CLIENTE
                $trans->createM2C($idCliente, $vlrCliente, $idPedido);

                // TRANSFÊNCIA EMPRESA TO MASTER
                $trans->createE2M($idEmpresa, $vlrCliente, $dtPrevisao, $idPedido);

                // TRANSFÊNCIA EMPRESA TO ADMIN
                $trans->createE2ADM($idEmpresa, $vlrAdmin, $dtPrevisao, $idPedido);

                // TRANSFÊNCIA EMPRESA TO ADQ
                $trans->createE2ADQ($idEmpresa, $vlrAdq, $dtPrevisao, $idPedido);

                $pedido = \common\models\CB16PEDIDO::findOne($idPedido);
                $pedido->CB16_TRANS_CRIADAS = 1;
                $pedido->CB16_STATUS = \common\models\CB16PEDIDO::status_pago;
                $pedido->save();
            }
        }
    }

}

?>
