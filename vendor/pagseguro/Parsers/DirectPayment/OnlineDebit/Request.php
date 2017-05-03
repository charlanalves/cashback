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

namespace vendor\pagseguro\Parsers\DirectPayment\OnlineDebit;

/**
 * Request from the Online debit direct payment
 * @package vendor\pagseguro\Parsers\DirectPayment\OnlineDebit
 */
use vendor\pagseguro\Enum\Properties\BackwardCompatibility;
use vendor\pagseguro\Enum\Properties\Current;
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
use vendor\pagseguro\Parsers\Transaction\OnlineDebit\Response;

/**
 * Class Payment
 * @package vendor\pagseguro\Parsers\DirectPayment\OnlineDebit
 */
class Request extends Error implements Parser
{
    use BankName;
    use Basic;
    use Currency;
    use Item;
    use Method;
    use Mode;
    use ReceiverEmail;
    use Sender;
    use Shipping;

    /**
     * @param \vendor\pagseguro\Domains\Requests\DirectPayment\OnlineDebit $onlineDebit
     * @return array
     */
    public static function getData(\vendor\pagseguro\Domains\Requests\DirectPayment\OnlineDebit $onlineDebit)
    {
        $data = [];
        $properties = new BackwardCompatibility();
        return array_merge(
            $data,
            BankName::getData($onlineDebit, $properties),
            Basic::getData($onlineDebit, $properties),
            Currency::getData($onlineDebit, $properties),
            Item::getData($onlineDebit, $properties),
            Method::getData($properties),
            Mode::getData($onlineDebit, $properties),
            ReceiverEmail::getData($onlineDebit, $properties),
            Sender::getData($onlineDebit, $properties),
            Shipping::getData($onlineDebit, $properties),
            Split::getData($onlineDebit, $properties)
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
            ->setRecoveryCode(current($xml->recoveryCode))
            ->setType(current($xml->type))
            ->setStatus(current($xml->status))
            ->setLastEventDate(current($xml->lastEventDate))
            ->setCancelationSource(current($xml->cancelationSource))
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
            ->setCreditorFees($xml->creditorFees)
            ->setApplication($xml->applications)
            ->setShipping($xml->shipping);
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
