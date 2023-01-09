<?php
/**
 * Main application entry point dispatcher: starts the 
 * application's UI for the current user, or logs them in
 * as necessary.
 *
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 *
 * @see Application_Bootstrap_Screen_Main
 *
 * @template-version 1.2
 */

declare(strict_types=1);

/**
 * The bootstrapper that starts the target application screen.
 * @see Application_Bootstrap
 */
require_once __DIR__ . '/bootstrap.php';

Application_Bootstrap::bootClass(Application_Bootstrap_Screen_Main::class);
