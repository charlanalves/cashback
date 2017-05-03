<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vendor\pagseguro\Enum\DirectPayment;

/**
 * Lists all the available payment methods for direct payment
 *
 * @package vendor\pagseguro\Enum\DirectPayment
 */
class Method
{
    const BOLETO = 'boleto';

    const CREDIT_CARD = 'creditCard';
    
    const ONLINE_DEBIT = 'eft';
}
