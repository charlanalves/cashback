<?php

require_once "../../vendor/autoload.php";

\vendor\pagamento\pagseguro\Library::initialize();
\vendor\pagamento\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagamento\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

/**
 * @var transaction code
 */
$code = "0B64FD7B4F9641378E9C9462982A8B95";

/**
 * @var value to refund
 * @optional true
 */
$value = null;

try {
    $refund = \vendor\pagamento\pagseguro\Services\Transactions\Refund::create(
        \vendor\pagamento\pagseguro\Configuration\Configure::getAccountCredentials(),
        $code,
        $value
    );

    echo "<pre>";
    print_r($refund);
} catch (Exception $e) {
    die($e->getMessage());
}
