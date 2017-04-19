<?php

require_once "../../../vendor/autoload.php";

\vendor\pagamento\pagseguro\Library::initialize();

$code = 'FD3AF1B214EC40F0B0A6745D041BF50D';

try {
    $response = \vendor\pagamento\pagseguro\Services\Application\Search\Code::search(
        \vendor\pagamento\pagseguro\Configuration\Configure::getApplicationCredentials(),
        $code
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
