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

namespace vendor\pagseguro\Resources\Factory\Split;

use vendor\pagseguro\Domains\Document;
use vendor\pagseguro\Domains\Phone;
use vendor\pagseguro\Enum\Properties\BackwardCompatibility;
use vendor\pagseguro\Enum\Properties\Current;

/**
 * Class Shipping
 * @package vendor\pagseguro\Resources\Factory\Request
 */
class Receiver
{

    /**
     * @var \vendor\pagseguro\Domains\Split\Receiver
     */
    private $receiver;
    private $split;

    /**
     * Receiver constructor.
     */
    public function __construct($split)
    {
        $this->split = $split;
        $this->receiver = new \vendor\pagseguro\Domains\Split\Receiver();
    }

    /**
     * @param \vendor\pagseguro\Domains\Split\Receiver $receiver
     * @return \vendor\pagseguro\Domains\Split\Receiver
     */
    public function instance(\vendor\pagseguro\Domains\Split\Receiver $receiver)
    {
        $this->split->setReceivers($receiver);
        return $this->split;
    }

    /**
     * @param $array
     * @return \vendor\pagseguro\Domains\Split\Receiver
     */
    public function withArray($array)
    {
        $properties = new BackwardCompatibility();

        $this->receiver->setPublicKey($array[$properties::RECEIVER_PUBLIC_KEY])
                       ->setAmount($properties::RECEIVER_SPLIT_AMOUNT)
                       ->setRatePercent($properties::RECEIVER_SPLIT_RATE_PERCENT)
                       ->setFeePercent($properties::RECEIVER_SPLIT_FEE_PERCENT);
        $this->split->setReceivers($this->receiver);
        return $this->split;
    }

    /**
     * @param $publicKey
     * @param $amount
     * @param $ratePercent
     * @param $feePercent
     * @return \vendor\pagseguro\Domains\Split\Receiver
     */
    public function withParameters(
        $publicKey,
        $amount,
        $ratePercent,
        $feePercent
    ) {
        $this->receiver->setPublicKey($publicKey)
                       ->setAmount($amount)
                       ->setRatePercent($ratePercent)
                       ->setFeePercent($feePercent);
        $this->split->setReceivers($this->receiver);
        return $this->split;
    }
}
