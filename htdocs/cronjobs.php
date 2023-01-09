<?php
/**
 * Cronjob script that performs regular cleanup operations.
 * Does not generate any output by default, except in case of an error.
 * 
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @template-version 1.2
 *
 * @request-param debug yes/no Rollback DB transactions, display log messages.
 *
 * @see Application_Bootstrap_Screen_Cronjobs
 */

declare(strict_types=1);

/**
 * The bootstrapper that starts the target application screen.
 * @see Application_Bootstrap
 */
require_once __DIR__ . '/bootstrap.php';

Application_Bootstrap::bootClass(Application_Bootstrap_Screen_Cronjobs::class);
