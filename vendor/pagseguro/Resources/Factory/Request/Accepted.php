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

namespace vendor\pagseguro\Resources\Factory\Request;

use vendor\pagseguro\Domains\PaymentMethod\Groups;
use vendor\pagseguro\Domains\PaymentMethod\Names;
use vendor\pagseguro\Enum\Properties\Current;

/**
 * Class Metadata
 * @package vendor\pagseguro\Resources\Factory\Request
 */
class Accepted
{
    /**
     * @var
     */
    private $accepted;


    public function __construct()
    {
        if (is_null($this->accepted)) {
            $this->accepted = new \vendor\pagseguro\Domains\PaymentMethod\Accepted();
        }
    }

    /**
     * @param $group
     * @return \vendor\pagseguro\Domains\PaymentMethod\Accepted
     */
    public function group($group)
    {
        $this->accepted->setGroups($group);
        return $this->accepted;
    }

    /**
     * @return \vendor\pagseguro\Domains\PaymentMethod\Accepted
     */
    public function groups()
    {
        foreach (func_get_args() as $args) {
            $this->accepted->setGroups($args);
        }
        return $this->accepted;
    }

    /**
     * @param $name
     * @return \vendor\pagseguro\Domains\PaymentMethod\Accepted
     */
    public function name($name)
    {
        $this->accepted->setNames($name);
        return $this->accepted;
    }

    /**
     * @return \vendor\pagseguro\Domains\PaymentMethod\Accepted
     */
    public function names()
    {
        foreach (func_get_args() as $args) {
            $this->accepted->setNames($args);
        }
        return $this->accepted;
    }
}
