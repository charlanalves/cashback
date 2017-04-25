<?php

namespace common\components;

use yii\base\Component;
use vendor\pagamento\pagseguro\Library as PagSeguro;
use vendor\pagamento\lib\iugu as Iugu;

/**
 * PagamentoComponent
 * Classe responsavel pelas formas de pagamento
 * */
class PagamentoComponent extends Component {

    // constantes PagSeguro
    const PagSeguroAmbiente = 'sandbox'; // production or sandbox
    const PagSeguroEmail = '';
    const PagSeguroToken = '';
    const PagSeguroAppID = '';
    const PagSeguroAppKey = '';
    const PagseguroHashRecebedorPrimario = '';
    const PagseguroRedirectUrl = '';
    const PagseguroNotificationUrl = '';

    // constantes Iugu
    const IuguId = '5F0CBE968FA94098816EF27F6FA77C36';
    const IuguToken = '67dfbb3560a62cb5cee9ca8730737a98'; 

    private function pagseguroInitialize() {
        PagSeguro::initialize();
        PagSeguro::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        PagSeguro::moduleVersion()->setName("Nome")->setRelease("1.0.0");
        
    }

    private function pagseguroSetConfigDefault(&$pag, $data) {

        // Set the Payment Mode for this payment request
        $pag->setMode('DEFAULT');

        // Set the currency
        $pag->setCurrency("BRL");

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        if (empty($data['cod_transacao'])) {
            $pag->setReference($data['cod_transacao']);
        }

        // Add an item for this payment request
        if (is_array($data['item'])) {
            foreach ($data['item'] as $p) {
                $pag->addItems()->withParameters(
                    $p['item_cod'], $p['item_desc'], $p['item_qtd'], $p['item_vlr']
                );
            }
        }

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $pag->setSender()->setName($data['comprador_nome']);
        $pag->setSender()->setEmail($data['comprador_email']);

        // set CPF or CNPJ
        $pag->setSender()->setDocument()->withParameters(
            'CPF', // CPF or CNPJ
            $data['comprador_cpf'] // numero do CPF
        );

        // set tel
        $pag->setSender()->setPhone()->withParameters(
            $data['comprador_tel_ddd'], $data['comprador_tel_numero']
        );

        // hash pagseguro do remetente
        $pag->setSender()->setHash($data['hash_recebedor_primario']);

        // $pag->setSender()->setIp('127.0.0.0');
        // Set shipping information for this payment request
        $pag->setShipping()->setAddress()->withParameters(
            $data['endereco_logradouro'], $data['endereco_numero'], $data['endereco_bairro'], $data['endereco_cep'], $data['endereco_cidade'], $data['endereco_uf'], $data['endereco_pais'], $data['endereco_complemento']
        );

        // Add a primary receiver for split this payment request - vendedor key
        $pag->setSplit()->setPrimaryReceiver(self::PagseguroHashRecebedorPrimario);

        // Add an receiver for split this payment request
        $pag->setSplit()->addReceiver()->withParameters(
//            $data['hash_recebedor_secundario'], $data['valor_total'], 20, 0
            '', $data['valor_total'], 20, 0
        );
    }

    private function pagseguroSetConfigCreditCard(&$pag, $data) {

        //Set billing information for credit card
        $pag->setBilling()->setAddress()->withParameters(
            $data['endereco_logradouro'], $data['endereco_numero'], $data['endereco_bairro'], $data['endereco_cep'], $data['endereco_cidade'], $data['endereco_uf'], $data['endereco_pais'], $data['endereco_complemento']
        );

        // Set credit card token
        $pag->setToken($data['cartao_token']);

        // Set the installment quantity and value (could be obtained using the Installments 
        // service, that have an example here in \public\getInstallments.php)
        $pag->setInstallment()->withParameters(
            $data['cartao_num_parcela'], $data['cartao_vlr_parcela']
        );

        // Set the credit card holder information
        $pag->setHolder()->setBirthdate($data['comprador_data_nascimento']);
        $pag->setHolder()->setName($data['cartao_nome']); // Equals in Credit Card

        $pag->setHolder()->setPhone()->withParameters(
            $data['comprador_tel_ddd'], $data['comprador_tel_numero']
        );

        $pag->setHolder()->setDocument()->withParameters(
            'CPF', // CPF or CNPJ
            $data['comprador_cpf'] // numero do CPF
        );
    }

    private function pagseguroSetConfigBoleto(&$pag, $data) {
        return;
    }

    private function pagseguroSetConfigOnlineDebit(&$pag, $data) {
        return;
    }

    private function pagseguroRegister(&$pag) {
        //Get the crendentials and register the payment
        $result = $pag->register(\vendor\pagseguro\Configuration\Configure::getApplicationCredentials());
        return $result;
    }

    // formas de pagamento [CreditCard, Boleto, OnlineDebit]
    private function pagseguroProcessCheckout($formPagamento, $data) {

        try {

            $ClassNamePagamento = '\vendor\pagseguro\Domains\Requests\DirectPayment\\' . $formPagamento;
            $metodoNameConfigPagamento = "pagseguroSetConfig" . $formPagamento;

            $this->pagseguroInitialize();
            $this->pagseguroSetConfigCredentials();
            $pag = new $ClassNamePagamento;
            $this->pagseguroSetConfigDefault($pag, $data);
            $this->{$metodoNameConfigPagamento}($pag, $data);
            $result = $this->pagseguroRegister($pag);
            $r['status'] = true;
            $r['return'] = $result;
        } catch (Exception $e) {
            $r['status'] = false;
            $r['return'] = $e->getMessage();
        }

        return $r;
    }

    // pagamento com pagseguro - CreditCard
    public function pagseguroCreditCard($data) {
        return $this->pagseguroProcessCheckout("CreditCard", $data);
    }

    // pagamento com pagseguro - Boleto
    public function pagseguroBoleto($data) {
        return $this->pagseguroProcessCheckout("Boleto", $data);
    }

    // pagamento com pagseguro - OnlineDebit
    public function pagseguroOnlineDebit($data) {
        return $this->pagseguroProcessCheckout("OnlineDebit", $data);
    }

    // Configura credenciais
    private function pagseguroSetConfigCredentials() {
        \vendor\pagseguro\Configuration\Configure::setEnvironment(self::PagSeguroAmbiente);

        \vendor\pagseguro\Configuration\Configure::setAccountCredentials(
                self::PagSeguroEmail, self::PagSeguroToken
        );

        \vendor\pagseguro\Configuration\Configure::setCharset('UTF-8'); // UTF-8 or ISO-8859-1

        \vendor\pagseguro\Configuration\Configure::setApplicationCredentials(
            self::PagSeguroAppID,
            self::PagSeguroAppKey
        );
        
        \vendor\pagseguro\Configuration\Configure::getApplicationCredentials()
                ->setAuthorizationCode('CAD7FD497171182114F7FFB2AD3FF2D5');
        
        // \vendor\pagseguro\Configuration\Configure::setLog(true, '/logpath/logFilename.log');

    }

    // cria sessao do comprador
    public function pagseguroCreateSession() {
        try {
            $this->pagseguroInitialize();
            $this->pagseguroSetConfigCredentials();
            $sessionCredentials = \vendor\pagseguro\Configuration\Configure::getAccountCredentials();
            $sessionCode = \vendor\pagseguro\Services\Session::create($sessionCredentials);
            return $sessionCode->getResult();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // cria Autorizacao para o vendedor
    public function pagseguroCreateAutorization() {
        try {
            $this->pagseguroInitialize();
            $this->pagseguroSetConfigCredentials();
            
            $authorization = new \vendor\pagseguro\Domains\Requests\Authorization();

            $authorization->setReference("AUTH_LIB_PHP_0001");
            $authorization->setRedirectUrl(self::PagseguroRedirectUr);
            $authorization->setNotificationUrl(self::PagseguroNotificationUrl);
            
            $authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::CREATE_CHECKOUTS);
            $authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::SEARCH_TRANSACTIONS);
            $authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::RECEIVE_TRANSACTION_NOTIFICATIONS);
            $authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::MANAGE_PAYMENT_PRE_APPROVALS);
            $authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::DIRECT_PAYMENT);

            $response = $authorization->register(
                \vendor\pagseguro\Configuration\Configure::getApplicationCredentials()
            );
            return $response;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}

?>
