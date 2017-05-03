<?php

require_once "../../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();

$code = 'DF7EB0AC9999333CC4379F82114239AB';

try {
    $response = \vendor\pagseguro\Services\PreApproval\Search\Code::search(
        \vendor\pagseguro\Configuration\Configure::getAccountCredentials(),
        $code
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
