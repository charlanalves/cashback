<?php
/**
 * 2007-2016 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    PagSeguro Internet Ltda.
 * @copyright 2007-2016 PagSeguro Internet Ltda.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 *
 */

require_once "../../vendor/autoload.php";

\vendor\pagseguro\Library::initialize();
\vendor\pagseguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\vendor\pagseguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

/*
 * To do a dynamic configuration of the library credentials you have to use the set methods
 * from the static class \vendor\pagseguro\Configuration\Configure. 
 */

//For example, to configure the library dynamically:
\vendor\pagseguro\Configuration\Configure::setEnvironment('production');//production or sandbox
\vendor\pagseguro\Configuration\Configure::setAccountCredentials(
    'your_pagseguro_email',
    'your_pagseguro_token'
);
\vendor\pagseguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
\vendor\pagseguro\Configuration\Configure::setLog(true, '/logpath/logFilename.log');

/**
 * @todo To set the application credentials intead of the account credentials use:
 * \vendor\pagseguro\Configuration\Configure::setApplicationCredentials(
 *  'appKey',
 *  'appId'
);
 */

try {
    /**
     * @todo For use with application credentials use:
     * \vendor\pagseguro\Configuration\Configure::getApplicationCredentials()
     *  ->setAuthorizationCode("FD3AF1B214EC40F0B0A6745D041BFDDD")
     */
    $sessionCode = \vendor\pagseguro\Services\Session::create(
        \vendor\pagseguro\Configuration\Configure::getAccountCredentials()
    );

    echo "<strong>ID de sess&atilde;o criado: </strong>{$sessionCode->getResult()}";
} catch (Exception $e) {
    die($e->getMessage());
}
