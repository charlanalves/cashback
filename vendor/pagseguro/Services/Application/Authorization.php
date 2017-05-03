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

namespace vendor\pagseguro\Services\Application;

use vendor\pagseguro\Domains\Account\Credentials;
use vendor\pagseguro\Helpers\Crypto;
use vendor\pagseguro\Resources\Connection;
use vendor\pagseguro\Resources\Http;
use vendor\pagseguro\Resources\Log\Logger;
use vendor\pagseguro\Resources\Responsibility;

class Authorization
{

    public static function create(Credentials $credentials, \vendor\pagseguro\Domains\Requests\Authorization $authorization)
    {
        Logger::info("Begin", ['service' => 'Authorization']);
        try {
            $connection = new Connection\Data($credentials);
            $http = new Http();
            Logger::info(sprintf("POST: %s", self::request($connection)), ['service' => 'Authorization']);
            Logger::info(
                sprintf(
                    "Params: %s",
                    json_encode(Crypto::encrypt(\vendor\pagseguro\Parsers\Authorization\Request::getData($authorization)))
                ),
                ['service' => 'Checkout']
            );
            $http->post(
                self::request($connection),
                \vendor\pagseguro\Parsers\Authorization\Request::getData($authorization)
            );

            $response = Responsibility::http(
                $http,
                new \vendor\pagseguro\Parsers\Authorization\Request
            );
            Logger::info(
                sprintf(
                    "Authorization URL: %s",
                    self::response($connection, $response)
                ),
                ['service' => 'Authorization']
            );
            return self::response($connection, $response);
        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(), ['service' => 'Authorization']);
            throw $exception;
        }
    }

    /**
     * @param Connection\Data $connection
     * @return string
     */
    private static function request(Connection\Data $connection)
    {
        return $connection->buildAuthorizationRequestUrl() ."?". $connection->buildCredentialsQuery();
    }

    /**
     * @param Connection\Data $connection
     * @param $response
     * @return string
     */
    private static function response(Connection\Data $connection, $response)
    {
        return $connection->buildAuthorizationResponseUrl() . "?code=" . $response->getCode();
    }
}
