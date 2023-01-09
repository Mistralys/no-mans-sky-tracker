<?php
/**
 * Bootstrapper for the unit test suites.
 *
 * @package NMSTracker
 * @subpackage UnitTests
 */

$configFile = __DIR__.'/config.php';

const TESTS_ROOT = __DIR__;
const APP_TESTS_RUNNING = true;

/**
* The bootstrapper that starts the target application screen.
* @see Application_Bootstrap
*/
require_once 'bootstrap.php';

if(!file_exists($configFile))
{
    die('The tests configuration file [/htdocs/tests/config.php] does not exist, please create it first.');
}

require $configFile;

Application_Bootstrap::boot('TestsSuite');
