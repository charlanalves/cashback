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

namespace vendor\pagamento\pagseguro\Resources\Responsibility\Configuration;

use vendor\pagamento\pagseguro\Resources\Responsibility\Handler;

class Wrapper implements Handler
{
    private $successor;

    public function successor($next)
    {
        $this->successor = $next;
        return $this;
    }

    public function handler($action, $class)
    {
        if (class_exists('ConfigWrapper')) {
            $configWrapper = new \ConfigWrapper;
            return array_merge(
                \vendor\pagamento\pagseguro\Helpers\Wrapper::environment($configWrapper),
                \vendor\pagamento\pagseguro\Helpers\Wrapper::credentials($configWrapper),
                \vendor\pagamento\pagseguro\Helpers\Wrapper::charset($configWrapper),
                \vendor\pagamento\pagseguro\Helpers\Wrapper::log($configWrapper)
            );
        }
        return $this->successor->handler($action, $class);
    }
}
