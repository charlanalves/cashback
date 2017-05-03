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

namespace vendor\pagseguro\Services\DirectPayment;

use vendor\pagseguro\Domains\Account\Credentials;
use vendor\pagseguro\Helpers\Crypto;
use vendor\pagseguro\Helpers\Mask;
use vendor\pagseguro\Parsers\DirectPayment\CreditCard\Request;
use vendor\pagseguro\Resources\Connection;
use vendor\pagseguro\Resources\Http;
use vendor\pagseguro\Resources\Log\Logger;
use vendor\pagseguro\Resources\Responsibility;

/**
 * Class Payment
 * @package vendor\pagseguro\Services\DirectPayment
 */
class CreditCard
{
    /**
     * @param \vendor\pagseguro\Domains\Account\Credentials $credentials
     * @param \vendor\pagseguro\Domains\Requests\DirectPayment\OnlineDebit $payment
     * @return string
     * @throws \Exception
     */
    public static function checkout(
        Credentials $credentials,
        \vendor\pagseguro\Domains\Requests\DirectPayment\CreditCard $payment
    ) {
        Logger::info("Begin", ['service' => 'DirectPayment.CreditCard']);
        try {
            $connection = new Connection\Data($credentials);
            $http = new Http();
            Logger::info(sprintf("POST: %s", self::request($connection)), ['service' => 'DirectPayment.CreditCard']);
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
                sprintf("Credit Card Payment Code: %s", $response->getCode()),
                ['service' => 'DirectPayment.CreditCard']
            );

            return $response;
        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(), ['service' => 'DirectPayment.CreditCard']);
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
