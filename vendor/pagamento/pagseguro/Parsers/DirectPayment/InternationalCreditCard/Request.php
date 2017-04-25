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

namespace vendor\pagamento\pagseguro\Parsers\DirectPayment\InternationalCreditCard;

/**
 * Request from the Credit Card direct payment
 * @package vendor\pagamento\pagseguro\Parsers\DirectPayment\CreditCard
 */
use vendor\pagamento\pagseguro\Enum\Properties\BackwardCompatibility;
use vendor\pagamento\pagseguro\Enum\Properties\Current;
use vendor\pagamento\pagseguro\Parsers\Basic;
use vendor\pagamento\pagseguro\Parsers\Currency;
use vendor\pagamento\pagseguro\Parsers\DirectPayment\CreditCard\Installment;
use vendor\pagamento\pagseguro\Parsers\DirectPayment\CreditCard\Method;
use vendor\pagamento\pagseguro\Parsers\DirectPayment\CreditCard\Token;
use vendor\pagamento\pagseguro\Parsers\DirectPayment\Mode;
use vendor\pagamento\pagseguro\Parsers\Error;
use vendor\pagamento\pagseguro\Parsers\Item;
use vendor\pagamento\pagseguro\Parsers\Parser;
use vendor\pagamento\pagseguro\Parsers\ReceiverEmail;
use vendor\pagamento\pagseguro\Parsers\Sender;
use vendor\pagamento\pagseguro\Parsers\Split;
use vendor\pagamento\pagseguro\Resources\Http;
use vendor\pagamento\pagseguro\Parsers\Transaction\InternationalCreditCard\Response;

/**
 * Class Payment
 * @package vendor\pagamento\pagseguro\Parsers\DirectPayment\CreditCard
 */
class Request extends Error implements Parser
{
    use Basic;
    use Currency;
    use Installment;
    use Item;
    use Method;
    use Mode;
    use ReceiverEmail;
    use Sender;
    use Token;

    /**
     * @param \vendor\pagamento\pagseguro\Domains\Requests\DirectPayment\CreditCard $creditCard
     * @return array
     */
    public static function getData(\vendor\pagamento\pagseguro\Domains\Requests\Requests $creditCard)
    {
        $data = [];
        $properties = new BackwardCompatibility();
        return array_merge(
            $data,
            Basic::getData($creditCard, $properties),
            Currency::getData($creditCard, $properties),
            Installment::getData($creditCard, $properties),
            Item::getData($creditCard, $properties),
            Method::getData($properties),
            Mode::getData($creditCard, $properties),
            ReceiverEmail::getData($creditCard, $properties),
            Sender::getData($creditCard, $properties),
            Split::getData($creditCard, $properties),
            Token::getData($creditCard, $properties)
        );
    }

    /**
     * @param \vendor\pagamento\pagseguro\Resources\Http $http
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
            ->setGatewaySystem($xml->gatewaySystem);
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
