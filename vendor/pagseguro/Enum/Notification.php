<?php
/**
 * Created by PhpStorm.
 * User: esilva
 * Date: 18/04/16
 * Time: 19:28
 */

namespace vendor\pagseguro\Enum;

/**
 * Class Notification
 * @package vendor\pagseguro\Enum
 */
class Notification extends Enum
{

    /**
     *
     */
    const TRANSACTION = "transaction";
    /**
     *
     */
    const APPLICATION_AUTHORIZATION = "applicationAuthorization";
    /**
     *
     */
    const PRE_APPROVAL = "preApproval";
}
