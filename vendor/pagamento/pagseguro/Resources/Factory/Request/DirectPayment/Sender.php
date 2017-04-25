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

namespace vendor\pagamento\pagseguro\Resources\Factory\Request\DirectPayment;

use vendor\pagamento\pagseguro\Domains\Document;
use vendor\pagamento\pagseguro\Domains\Phone;
use vendor\pagamento\pagseguro\Enum\Properties\Current;

/**
 * Class Sender from Direct Payment
 * @package vendor\pagamento\pagseguro\Resources\Factory\Request\DirectPayment
 */
class Sender
{

    /**
     * @var \vendor\pagamento\pagseguro\Domains\Sender
     */
    private $sender;

    /**
     * Sender constructor.
     */
    public function __construct()
    {
        $this->sender = new \vendor\pagamento\pagseguro\Domains\DirectPayment\Sender();
    }

    /**
     * @param \vendor\pagamento\pagseguro\Domains\Sender $sender
     * @return \vendor\pagamento\pagseguro\Domains\Sender
     */
    public function instance(\vendor\pagamento\pagseguro\Domains\DirectPayment\Sender $sender)
    {
        return $sender;
    }

    /**
     * @param $array
     * @return \vendor\pagamento\pagseguro\Domains\Shipping
     */
    public function withArray($array)
    {
        $properties = new Current;
        return $this->sender->setName($array[$properties::SENDER_NAME])
                            ->setEmail($array[$properties::SENDER_EMAIL])
                            ->setPhone($array["phone"])
                            ->setDocuments($array["document"])
                            ->setIp($array[$properties::SENDER_IP]);
    }

    /**
     * @param $name
     * @param $email
     * @param Phone $phone
     * @param Document $document
     * @param $ip
     * @return \vendor\pagamento\pagseguro\Domains\DirectPayment\Sender
     */
    public function withParameters(
        $name,
        $email,
        Phone $phone,
        Document $document,
        $ip
    ) {
        $this->sender->setName($name)
               ->setEmail($email)
               ->setPhone($phone)
               ->setDocuments($document)
               ->setIp($ip);
        return $this->sender;
    }
    
    public function setHash($hash)
    {
        $this->sender->setHash($hash);
        return $this->sender;
    }
}
