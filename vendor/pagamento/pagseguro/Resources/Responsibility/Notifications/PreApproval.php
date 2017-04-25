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

namespace vendor\pagamento\pagseguro\Resources\Responsibility\Notifications;

use vendor\pagamento\pagseguro\Helpers\Xhr;

/**
 * Class PreApproval
 * @package vendor\pagamento\pagseguro\Resources\Responsibility\Notifications
 */
class PreApproval implements \vendor\pagamento\pagseguro\Resources\Responsibility\Notifications\Handler
{

    /**
     * @var
     */
    private $successor;

    /**
     * @param $next
     * @return $this
     */
    public function successor($next)
    {
        $this->successor = $next;
        return $this;
    }

    /**
     * @return mixed
     */
    public function handler()
    {
        if (!is_null(Xhr::getInputCode()) and
            !is_null(Xhr::getInputType()) and
            Xhr::getInputType() == \vendor\pagamento\pagseguro\Enum\Notification::PRE_APPROVAL) {
            $notification = \vendor\pagamento\pagseguro\Helpers\NotificationObject::initialize();
            return $notification->getCode();
        }
        return $this->successor->handler();
    }
}
