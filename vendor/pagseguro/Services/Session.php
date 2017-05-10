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

namespace vendor\pagseguro\Services;

use vendor\pagseguro\Domains\Account\Credentials;
use vendor\pagseguro\Parsers\Session\Request;
use vendor\pagseguro\Resources\Connection;
use vendor\pagseguro\Resources\Http;
use vendor\pagseguro\Resources\Log\Logger;
use vendor\pagseguro\Resources\Responsibility;

/**
 * The Session Service class
 *
 */
class Session
{
    public static function create(Credentials $credentials)
    {

        Logger::info("Begin", ['service' => 'Session']);

        try {
            $connection = new Connection\Data($credentials);
            $http = new Http();
            Logger::info(sprintf("POST: %s", self::request($connection)), ['service' => 'Session']);
            $http->post(self::request($connection));

            $response = Responsibility::http(
                $http,
                new Request()
            );

            Logger::info(sprintf("Session ID: %s", current($response)), ['service' => 'Session']);
            return $response;
        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(), ['service' => 'Session']);
            throw $exception;
        }
    }
    
    private static function request(Connection\Data $connection)
    {

        return $connection->buildSessionRequestUrl() . "?" . $connection->buildCredentialsQuery();
    }
}
