<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vendor\pagamento\pagseguro\Services;

use vendor\pagamento\pagseguro\Domains\Account\Credentials;
use vendor\pagamento\pagseguro\Enum\Properties\Current;
use vendor\pagamento\pagseguro\Helpers\Currency;
use vendor\pagamento\pagseguro\Parsers\Installment\Request;
use vendor\pagamento\pagseguro\Resources\Connection;
use vendor\pagamento\pagseguro\Resources\Http;
use vendor\pagamento\pagseguro\Resources\Log\Logger;
use vendor\pagamento\pagseguro\Resources\Responsibility;

/**
 * Description of Installment
 *
 */
class Installment
{
    /**
     * @param Credentials $credentials
     * @param mixed $params
     * @return Pagseguro\Domains\Responses\Installments
     * @throws \Exception
     */
    public static function create(Credentials $credentials, $params)
    {
        Logger::info("Begin", ['service' => 'Installment']);
        try {
            $connection = new Connection\Data($credentials);
            $http = new Http();
            Logger::info(sprintf("GET: %s", self::request($connection, $params)), ['service' => 'Installment']);
            $http->get(self::request($connection, $params));

            $response = Responsibility::http(
                $http,
                new Request
            );

            return $response;
        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(), ['service' => 'Installment']);
            throw $exception;
        }
    }
    
    /**
     * Build the service request url
     * @param \vendor\pagamento\pagseguro\Resources\Connection\Data $connection
     * @param mixed $params
     * @return string
     */
    private static function request(Connection\Data $connection, $params)
    {
        return sprintf(
            "%s?%s%s%s%s",
            $connection->buildInstallmentRequestUrl(),
            $connection->buildCredentialsQuery(),
            sprintf("&%s=%s", Current::INSTALLMENT_AMOUNT, Currency::toDecimal($params['amount'])),
            is_null($params['card_brand']) ?:
                sprintf("&%s=%s", Current::INSTALLMENT_CARD_BRAND, $params['card_brand']),
            is_null($params['max_installment_no_interest']) ? '' :
                sprintf(
                    "&%s=%s",
                    Current::INSTALLMENT_MAX_INSTALLMENT_NO_INTEREST,
                    $params['max_installment_no_interest']
                )
        );
    }
}
