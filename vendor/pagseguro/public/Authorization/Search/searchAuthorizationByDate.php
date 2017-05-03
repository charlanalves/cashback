<?php

require_once "../../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();

$options = [
    'initial_date' => '2015-09-09T00:00',
    'final_date' => '2015-09-12T09:55', //Optional
    'page' => 1, //Optional
    'max_per_page' => 20, //Optional
];

try {
    $response = \vendor\pagseguro\Services\Application\Search\Date::search(
        \vendor\pagseguro\Configuration\Configure::getApplicationCredentials(),
        $options
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
