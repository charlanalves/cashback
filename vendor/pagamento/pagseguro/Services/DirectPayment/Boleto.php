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

namespace vendor\pagamento\pagseguro\Services\DirectPayment;

use vendor\pagamento\pagseguro\Domains\Account\Credentials;
use vendor\pagamento\pagseguro\Helpers\Crypto;
use vendor\pagamento\pagseguro\Helpers\Mask;
use vendor\pagamento\pagseguro\Resources\Connection;
use vendor\pagamento\pagseguro\Resources\Http;
use vendor\pagamento\pagseguro\Resources\Log\Logger;
use vendor\pagamento\pagseguro\Resources\Responsibility;
use vendor\pagamento\pagseguro\Parsers\DirectPayment\Boleto\Request;

/**
 * Class Payment
 * @package vendor\pagamento\pagseguro\Services\DirectPayment
 */
class Boleto
{

    /**
     * @param \vendor\pagamento\pagseguro\Domains\Account\Credentials $credentials
     * @param \vendor\pagamento\pagseguro\Domains\Requests\DirectPayment\Boleto $payment
     * @return string
     * @throws \Exception
     */
    public static function checkout(
        Credentials $credentials,
        \vendor\pagamento\pagseguro\Domains\Requests\DirectPayment\Boleto $payment
    ) {
        Logger::info("Begin", ['service' => 'DirectPayment.Boleto']);
        try {
            $connection = new Connection\Data($credentials);
            $http = new Http();
            Logger::info(
                sprintf("POST: %s", self::request($connection)),
                ['service' => 'DirectPayment.Boleto']
            );
            Logger::info(
                sprintf(
                    "Params: %s",
                    json_encode(Crypto::encrypt(Request::getData($payment)))
                ),
                ['service' => 'Checkout']
            );

            $http->post(
                self::request($connection),
                Request::getData($payment)
            );

            $response = Responsibility::http(
                $http,
                new Request
            );

            Logger::info(
                sprintf("Boleto Payment Link URL: %s", $response->getPaymentLink()),
                ['service' => 'DirectPayment.Boleto']
            );

            return $response;
        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(), ['service' => 'Session']);
            throw $exception;
        }
    }

    /**
     * @param Connection\Data $connection
     * @return string
     */
    private static function request(Connection\Data $connection)
    {
        return $connection->buildDirectPaymentRequestUrl() ."?". $connection->buildCredentialsQuery();
    }
}
