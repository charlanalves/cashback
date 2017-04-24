<?php

require_once "../../../vendor/autoload.php";

\vendor\pagamento\pagseguro\Library::initialize();

$code = 'F7A6F7CC09CB09CB84E664A1AFA3FD5D2481';

try {
    $response = \vendor\pagamento\pagseguro\Services\PreApproval\Search\Notification::search(
        \vendor\pagamento\pagseguro\Configuration\Configure::getAccountCredentials(),
        $code
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
