<?php

require_once "../../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();

$options = [
    'initial_date' => '2016-04-01T14:55',
    'final_date' => '2016-04-24T09:55', //Optional
    'page' => 1, //Optional
    'max_per_page' => 20, //Optional
];

$reference = "LIBPHP000001";

try {
    $response = \vendor\pagseguro\Services\Transactions\Search\Reference::search(
        \vendor\pagseguro\Configuration\Configure::getAccountCredentials(),
        $reference,
        $options
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
