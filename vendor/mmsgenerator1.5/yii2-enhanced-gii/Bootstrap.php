<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2014 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mmsgenerator15\enhancedgii;

use yii\base\Application;
use yii\base\BootstrapInterface;


/**
 * Class Bootstrap
 * @package mmsgenerator\mmsgenerator
 * @author Charlan Santos 10/11/2016
 */
class Bootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['mmsgenerator15'])) {
                $app->getModule('gii')->generators['mmsgenerator15-model'] = 'mmsgenerator15\enhancedgii\model\Generator';
                $app->getModule('gii')->generators['mmsgenerator15-crud']['class'] = 'mmsgenerator15\enhancedgii\crud\Generator';
                $app->getModule('gii')->generators['mmsgenerator15-migration'] = 'mmsgenerator15\enhancedgii\migration\Generator';
            }
        }
    }
}
