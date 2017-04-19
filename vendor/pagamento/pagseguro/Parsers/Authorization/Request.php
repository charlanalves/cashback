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

namespace vendor\pagamento\pagseguro\Parsers\Authorization;

use vendor\pagamento\pagseguro\Enum\Properties\Current;
use vendor\pagamento\pagseguro\Parsers\Basic;
use vendor\pagamento\pagseguro\Parsers\Error;
use vendor\pagamento\pagseguro\Parsers\Parser;
use vendor\pagamento\pagseguro\Resources\Http;

/**
 * Class Payment
 * @package vendor\pagamento\pagseguro\Parsers\Checkout
 */
class Request extends Error implements Parser
{

    use Basic;


    /**
     * @param \vendor\pagamento\pagseguro\Domains\Requests\Authorization $authorization
     * @return array
     */
    public static function getData(\vendor\pagamento\pagseguro\Domains\Requests\Authorization $authorization)
    {
        $data = [];
        $properties = new Current;

        if (!is_null($authorization->getPermissions())) {
            $data[$properties::PERMISSIONS] = $authorization->getPermissions();
        }

        return array_merge(
            $data,
            Basic::getData($authorization, $properties)
        );
    }

    /**
     * @param \vendor\pagamento\pagseguro\Resources\Http $http
     * @return Response
     */
    public static function success(Http $http)
    {
        $xml = simplexml_load_string($http->getResponse());
        return (new Response)->setCode(current($xml->code))
                             ->setDate(current($xml->date));
    }

    /**
     * @param \vendor\pagamento\pagseguro\Resources\Http $http
     * @return \vendor\pagamento\pagseguro\Domains\Error
     */
    public static function error(Http $http)
    {
        $error = parent::error($http);
        return $error;
    }
}
