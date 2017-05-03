<?php

require_once "../../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();

$code = '7BD4A616E8C3E8C3F57BB440FFA9ABEAE6F2';

try {
    $response = \vendor\pagseguro\Services\Application\Search\Notification::search(
        \vendor\pagseguro\Configuration\Configure::getApplicationCredentials(),
        $code
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
