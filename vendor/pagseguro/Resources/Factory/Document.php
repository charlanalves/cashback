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

namespace vendor\pagseguro\Resources\Factory;

use vendor\pagseguro\Enum\Properties\Current;

/**
 * Class Document
 * @package vendor\pagseguro\Resources\Factory
 */
class Document
{

    /**
     * @var \vendor\pagseguro\Domains\Document
     */
    private $document;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->document = new \vendor\pagseguro\Domains\Document();
    }

    /**
     * @param \vendor\pagseguro\Domains\Document $document
     * @return \vendor\pagseguro\Domains\Document
     */
    public function instance(\vendor\pagseguro\Domains\Document $document)
    {
        return $document;
    }

    /**
     * @param $array
     * @return \vendor\pagseguro\Domains\Document|Document
     */
    public function withArray($array)
    {
        $properties = new Current();
        $this->document->setType($array[$properties::DOCUMENT_TYPE])
                       ->setIdentifier($array[$properties::DOCUMENT_VALUE]);
        return $this->document;
    }

    /**
     * @param $type
     * @param $identifier
     * @return \vendor\pagseguro\Domains\Document
     */
    public function withParameters($type, $identifier)
    {
        $this->document->setType($type)
                       ->setIdentifier($identifier);
        return $this->document;
    }
}
