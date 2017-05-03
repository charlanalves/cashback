<?php

require_once "../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();
\vendor\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

/**
 * @var string PreApproval code
 */
$code = "DF7EB0AC9999333CC4379F82114239AB";

try {
    $response = \vendor\pagseguro\Services\PreApproval\Cancel::create(
        \vendor\pagseguro\Configuration\Configure::getAccountCredentials(),
        $code
    );

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}