<?php

require_once "../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();
\vendor\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

try {
    if (\vendor\pagseguro\Helpers\Xhr::hasPost()) {
        $response = \vendor\pagseguro\Services\Transactions\Notification::check(
            \vendor\pagseguro\Configuration\Configure::getAccountCredentials()
        );
    } else {
        throw new \InvalidArgumentException($_POST);
    }

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
