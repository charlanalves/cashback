<?php

require_once "../../vendor/autoload.php";

\vendor\pagamento\pagseguro\Library::initialize();
\vendor\pagamento\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagamento\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

try {
    if (\vendor\pagamento\pagseguro\Helpers\Xhr::hasPost()) {
        $response = \vendor\pagamento\pagseguro\Services\Application\Notification::check(
            \vendor\pagamento\pagseguro\Configuration\Configure::getApplicationCredentials()
        );
    } else {
        throw new \InvalidArgumentException($_POST);
    }

    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}
