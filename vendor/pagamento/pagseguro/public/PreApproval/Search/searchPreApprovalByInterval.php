<?php

require_once "../../../vendor/autoload.php";

\vendor\pagamento\pagseguro\Library::initialize();

$days = 20;

try {
    $response = \vendor\pagamento\pagseguro\Services\PreApproval\Search\Interval::search(
        \vendor\pagamento\pagseguro\Configuration\Configure::getAccountCredentials(),
        $days
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
