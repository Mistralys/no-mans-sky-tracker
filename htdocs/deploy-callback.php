<?php
/**
 * Deployment callback script that handles the one-time
 * tasks that need to be done after a deployment.
 *
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @template-version 1
 *
 * @see Application\Bootstrap\DeployCallbackBootstrap
 */

declare(strict_types=1);

use Application\Bootstrap\DeployCallbackBootstrap;

/**
* The bootstrapper that starts the target application screen.
* @see Application_Bootstrap
*/
require_once __DIR__ . '/bootstrap.php';

Application_Bootstrap::bootClass(DeployCallbackBootstrap::class);
