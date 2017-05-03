<?php

require_once "../../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();

$days = 20;

try {
    $response = \vendor\pagseguro\Services\PreApproval\Search\Interval::search(
        \vendor\pagseguro\Configuration\Configure::getAccountCredentials(),
        $days
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
