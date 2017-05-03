<?php

require_once "../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();
\vendor\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

$authorization = new \vendor\pagseguro\Domains\Requests\Authorization();

$authorization->setReference("AUTH_LIB_PHP_0001");
$authorization->setRedirectUrl("http://www.lojamodelo.com.br");
$authorization->setNotificationUrl("http://www.lojamodelo.com.br/nofitication");

$authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::CREATE_CHECKOUTS);
$authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::SEARCH_TRANSACTIONS);
$authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::RECEIVE_TRANSACTION_NOTIFICATIONS);
$authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::MANAGE_PAYMENT_PRE_APPROVALS);
$authorization->addPermission(\vendor\pagseguro\Enum\Authorization\Permissions::DIRECT_PAYMENT);

try {
    $response = $authorization->register(
        \vendor\pagseguro\Configuration\Configure::getApplicationCredentials()
    );
    echo "<h2>Criando requisi&ccedil;&atilde;o de authorização</h2>"
        . "<p>URL do pagamento: <strong>$response</strong></p>"
        . "<p><a title=\"URL de Autorização\" href=\"$response\" target=\_blank\">"
        . "Ir para URL de authorização.</a></p>";
} catch (Exception $e) {
    die($e->getMessage());
}
