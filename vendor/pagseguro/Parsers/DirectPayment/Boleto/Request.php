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

namespace vendor\pagseguro\Parsers\DirectPayment\Boleto;

/**
 * Request from the Boleto direct payment
 *
 * @package vendor\pagseguro\Parsers\DirectPayment\Boleto
 */
use vendor\pagseguro\Enum\Properties\BackwardCompatibility;
use vendor\pagseguro\Parsers\Basic;
use vendor\pagseguro\Parsers\Currency;
use vendor\pagseguro\Parsers\DirectPayment\Mode;
use vendor\pagseguro\Parsers\Error;
use vendor\pagseguro\Parsers\Item;
use vendor\pagseguro\Parsers\Parser;
use vendor\pagseguro\Parsers\ReceiverEmail;
use vendor\pagseguro\Parsers\Sender;
use vendor\pagseguro\Parsers\Shipping;
use vendor\pagseguro\Parsers\Split;
use vendor\pagseguro\Resources\Http;
use vendor\pagseguro\Parsers\Transaction\Boleto\Response;

/**
 * Class Payment
 * @package vendor\pagseguro\Parsers\DirectPayment\Boleto
 */
class Request extends Error implements Parser
{
    use Basic;
    use Currency;
    use Item;
    use Method;
    use Mode;
    use ReceiverEmail;
    use Sender;
    use Shipping;

    /**
     * @param \vendor\pagseguro\Domains\Requests\DirectPayment\Boleto $boleto
     * @return array
     */
    public static function getData(\vendor\pagseguro\Domains\Requests\DirectPayment\Boleto $boleto)
    {
        $data = [];

        $properties = new BackwardCompatibility();

        return array_merge(
            $data,
            Basic::getData($boleto, $properties),
            Currency::getData($boleto, $properties),
            Item::getData($boleto, $properties),
            Method::getData($properties),
            Mode::getData($boleto, $properties),
            ReceiverEmail::getData($boleto, $properties),
            Split::getData($boleto, $properties),
            Sender::getData($boleto, $properties),
            Shipping::getData($boleto, $properties)
        );
    }

    /**
     * @param \vendor\pagseguro\Resources\Http $http
     * @return Response
     */
    public static function success(Http $http)
    {
        $xml = simplexml_load_string($http->getResponse());

        return (new Response)->setDate(current($xml->date))
            ->setCode(current($xml->code))
            ->setReference(current($xml->reference))
            ->setType(current($xml->type))
            ->setStatus(current($xml->status))
            ->setLastEventDate(current($xml->lastEventDate))
            ->setCancelationSource(current($xml->cancelationSource))
            ->setCreditorFees($xml->creditorFees)
            ->setPaymentLink(current($xml->paymentLink))
            ->setPaymentMethod($xml->paymentMethod)
            ->setGrossAmount(current($xml->grossAmount))
            ->setDiscountAmount(current($xml->discountAmount))
            ->setFeeAmount(current($xml->feeAmount))
            ->setNetAmount(current($xml->netAmount))
            ->setExtraAmount(current($xml->extraAmount))
            ->setEscrowEndDate(current($xml->escrowEndDate))
            ->setInstallmentCount(current($xml->installmentCount))
            ->setItemCount(current($xml->itemCount))
            ->setItems($xml->items)
            ->setSender($xml->sender)
            ->setShipping($xml->shipping)
            ->setApplication($xml->applications);
    }

    /**
     * @param \vendor\pagseguro\Resources\Http $http
     * @return \vendor\pagseguro\Domains\Error
     */
    public static function error(Http $http)
    {
        return parent::error($http);
    }
}
