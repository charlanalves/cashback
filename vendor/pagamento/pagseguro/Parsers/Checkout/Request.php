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

namespace vendor\pagamento\pagseguro\Parsers\Checkout;

use vendor\pagamento\pagseguro\Enum\Properties\Current;
use vendor\pagamento\pagseguro\Parsers\Accepted;
use vendor\pagamento\pagseguro\Parsers\Basic;
use vendor\pagamento\pagseguro\Parsers\Currency;
use vendor\pagamento\pagseguro\Parsers\Error;
use vendor\pagamento\pagseguro\Parsers\Item;
use vendor\pagamento\pagseguro\Parsers\Parser;
use vendor\pagamento\pagseguro\Parsers\PaymentMethod;
use vendor\pagamento\pagseguro\Parsers\PreApproval;
use vendor\pagamento\pagseguro\Parsers\Sender;
use vendor\pagamento\pagseguro\Parsers\Shipping;
use vendor\pagamento\pagseguro\Parsers\Metadata;
use vendor\pagamento\pagseguro\Parsers\Parameter;
use vendor\pagamento\pagseguro\Parsers\ReceiverEmail;
use vendor\pagamento\pagseguro\Resources\Http;

/**
 * Class Payment
 * @package vendor\pagamento\pagseguro\Parsers\Checkout
 */
class Request extends Error implements Parser
{
    use Accepted;
    use Basic;
    use Currency;
    use Item;
    use PreApproval;
    use Sender;
    use Shipping;
    use Metadata;
    use ReceiverEmail;
    use Parameter;
    use PaymentMethod;

    /**
     * @param \vendor\pagamento\pagseguro\Domains\Requests\Payment $payment
     * @return array
     */
    public static function getData(\vendor\pagamento\pagseguro\Domains\Requests\Payment $payment)
    {
        $data = [];
        $properties = new Current;
        return array_merge(
            $data,
            Accepted::getData($payment, $properties),
            Basic::getData($payment, $properties),
            Currency::getData($payment, $properties),
            Item::getData($payment, $properties),
            PreApproval::getData($payment, $properties),
            Sender::getData($payment, $properties),
            Shipping::getData($payment, $properties),
            Metadata::getData($payment, $properties),
            Parameter::getData($payment),
            PaymentMethod::getData($payment, $properties),
            ReceiverEmail::getData($payment, $properties)
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
        return parent::error($http);
    }
}
