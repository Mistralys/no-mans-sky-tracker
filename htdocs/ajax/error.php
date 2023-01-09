<?php
/**
 * Logs failed AJAX requests.
 *  
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 *
 * @template-version 1.2
 * @see Application_Bootstrap_Screen_AjaxError
 */

declare(strict_types=1);

/**
 * The bootstrapper that starts the target application screen.
 * @see Application_Bootstrap
 */
require_once __DIR__ . '/../bootstrap.php';

Application_Bootstrap::bootClass(Application_Bootstrap_Screen_AjaxError::class);
