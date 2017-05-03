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

namespace vendor\pagseguro\Parsers\DirectPayment\CreditCard;

/**
 * Request from the Credit Card direct payment
 * @package vendor\pagseguro\Parsers\DirectPayment\CreditCard
 */
use vendor\pagseguro\Enum\Properties\BackwardCompatibility;
use vendor\pagseguro\Enum\Properties\Current;
use vendor\pagseguro\Parsers\Basic;
use vendor\pagseguro\Parsers\Currency;
use vendor\pagseguro\Parsers\DirectPayment\CreditCard\Billing;
use vendor\pagseguro\Parsers\DirectPayment\CreditCard\Holder;
use vendor\pagseguro\Parsers\DirectPayment\CreditCard\Installment;
use vendor\pagseguro\Parsers\DirectPayment\CreditCard\Token;
use vendor\pagseguro\Parsers\DirectPayment\Mode;
use vendor\pagseguro\Parsers\Error;
use vendor\pagseguro\Parsers\Item;
use vendor\pagseguro\Parsers\Parser;
use vendor\pagseguro\Parsers\ReceiverEmail;
use vendor\pagseguro\Parsers\Sender;
use vendor\pagseguro\Parsers\Shipping;
use vendor\pagseguro\Parsers\Split;
use vendor\pagseguro\Resources\Http;
use vendor\pagseguro\Parsers\Transaction\CreditCard\Response;

/**
 * Class Payment
 * @package vendor\pagseguro\Parsers\DirectPayment\CreditCard
 */
class Request extends Error implements Parser
{
    use Basic;
    use Currency;
    use Holder;
    use Installment;
    use Item;
    use Method;
    use Mode;
    use ReceiverEmail;
    use Sender;
    use Shipping;

    /**
     * @param \vendor\pagseguro\Domains\Requests\DirectPayment\CreditCard $creditCard
     * @return array
     */
    public static function getData(\vendor\pagseguro\Domains\Requests\DirectPayment\CreditCard $creditCard)
    {

        $data = [];
        $properties = new BackwardCompatibility();
        return array_merge(
            $data,
            Basic::getData($creditCard, $properties),
            Billing::getData($creditCard, $properties),
            Currency::getData($creditCard, $properties),
            Holder::getData($creditCard, $properties),
            Installment::getData($creditCard, $properties),
            Item::getData($creditCard, $properties),
            Method::getData($properties),
            Mode::getData($creditCard, $properties),
            ReceiverEmail::getData($creditCard, $properties),
            Sender::getData($creditCard, $properties),
            Shipping::getData($creditCard, $properties),
            Token::getData($creditCard, $properties),
            Split::getData($creditCard, $properties)
        );
    }

    /**
     * @param \vendor\pagseguro\Resources\Http $http
     * @return Response
     */
    public static function success(Http $http)
    {
        $xml = simplexml_load_string($http->getResponse());

        return (new Response)->setCancelationSource(current($xml->cancelationSource))
            ->setCode(current($xml->code))
            ->setDate(current($xml->date))
            ->setDiscountAmount(current($xml->discountAmount))
            ->setExtraAmount(current($xml->extraAmount))
            ->setEscrowEndDate(current($xml->escrowEndDate))
            ->setFeeAmount(current($xml->feeAmount))
            ->setGatewaySystem($xml->gatewaySystem)
            ->setGrossAmount(current($xml->grossAmount))
            ->setInstallmentCount(current($xml->installmentCount))
            ->setItemCount(current($xml->itemCount))
            ->setItems($xml->items)
            ->setLastEventDate(current($xml->lastEventDate))
            ->setNetAmount(current($xml->netAmount))
            ->setPaymentMethod($xml->paymentMethod)
            ->setReference(current($xml->reference))
            ->setSender($xml->sender)
            ->setShipping($xml->shipping)
            ->setStatus(current($xml->status))
            ->setCreditorFees($xml->creditorFees)
            ->setApplication($xml->applications)
            ->setType(current($xml->type));
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
