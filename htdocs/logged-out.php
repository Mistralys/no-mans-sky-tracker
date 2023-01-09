<?php
/**
 * Simple logout page that is displayed after the user has logged out,
 * to give him the possibility to log back in or go somewhere else.
 *
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 *
 * @see Application_Bootstrap_Screen_LoggedOut
 *
 * @template-version 1.3
 */

declare(strict_types=1);

/**
 * The bootstrapper that starts the target application screen.
 * @see Application_Bootstrap
 */
require_once __DIR__ . '/bootstrap.php';

Application_Bootstrap::bootClass(Application_Bootstrap_Screen_LoggedOut::class);
