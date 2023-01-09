<?php
/**
 * Main bootstrapper file used to set up the Application
 * environment.
 *
 * @package NMSTracker
 * @subpackage Dispatchers
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 *
 * @template-version 1.1
 */

declare(strict_types=1);

/**
 * The application root folder (this file's location)
 */
const APP_ROOT = __DIR__;

/**
 * The folder in which the Application libraries
 * are installed. This is the default using a
 * composer structure.
 */
const APP_INSTALL_FOLDER = __DIR__ . '/vendor/mistralys/application_framework/src';

/**
 * The bootstrapper class that configures the
 * application environment.
 *
 * @see Application_Bootstrap
 */
require_once APP_INSTALL_FOLDER . '/classes/Application/Bootstrap.php';

// The initialization includes the local configuration files,
// and defines all global application settings.
Application_Bootstrap::init();
