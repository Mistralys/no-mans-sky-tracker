<?php
/**
 * Template for the local configuration file.
 * @package NMSTracker
 * @subpackage Core
 */

const ENVIRONMENT_TEST_SUITES = 'test-suites';
const ENVIRONMENT_PRODUCTION = 'prod-pigeon';

// --------------------------------------------------------
// DEFAULT CONFIGURATION SETTINGS
// --------------------------------------------------------

$dataPath = '/data';

// Simulate a user session, and bypass login?
$APP_SIMULATE_SESSION = false;

// Serve the javascript files as minified?
$APP_JAVASCRIPT_MINIFIED = true;

// set main app set
$APP_APPSET = 'main';

// only available for hosting
$APP_INSTANCE_ID = 'hosting';

$APP_DEEPL_PROXY_ENABLED = false;

// ---------------------------------------------------
// Unit tests database
// ---------------------------------------------------
$APP_DB_TESTS_NAME = '';
$APP_DB_TESTS_USER = '';
$APP_DB_TESTS_PORT = '';
$APP_DB_TESTS_PASSWORD = '';
$APP_DB_TESTS_HOST = '';

// ---------------------------------------------------
// Database defaults
// ---------------------------------------------------
$APP_DB_PORT = '3306';

// --------------------------------------------------------
// ENVIRONMENT-SPECIFIC SETTINGS
// --------------------------------------------------------
boot_define('APP_LOGGING_ENABLED', true);

$environment = config_detect_environment();
$loadDevConfig = false;
$loadTestConfig = false;

switch ($environment->getID())
{
    case ENVIRONMENT_TEST_SUITES:
        $APP_SIMULATE_SESSION = true;
        $APP_JAVASCRIPT_MINIFIED = false;

        $testConfig = __DIR__ . '/config-env-test.php';
        if (!file_exists($testConfig)) {
            die('The file ' . basename($testConfig) . ' does not exist.');
        }

        require_once $testConfig;
        break;

    case ENVIRONMENT_PRODUCTION:
        $APP_URL = '';
        $APP_DB_NAME = '';
        $APP_DB_USER = '';
        $APP_DB_PASSWORD = '';
        $APP_DB_HOST = '';
        break;

    default:
        $APP_SIMULATE_SESSION = true;
        $APP_JAVASCRIPT_MINIFIED = false;
        $loadDevConfig = true;
        break;
}

// --------------------------------------------------------
// LOCAL DEVELOPER CONFIGURATION SETTINGS
// --------------------------------------------------------
if ($loadDevConfig) {
    $devConfig = __DIR__ . '/config-env-local.php';
    if (!file_exists($devConfig)) {
        die('The file ' . basename($devConfig) . ' does not exist.');
    }

    require_once $devConfig;
}

// --------------------------------------------------------
// APPLY THE SETTINGS
// --------------------------------------------------------

boot_define('APP_URL', $APP_URL);
boot_define('APP_INSTANCE_ID', $APP_INSTANCE_ID);
boot_define('APP_APPSET', $APP_APPSET);

boot_define('APP_DEEPL_PROXY_ENABLED', $APP_DEEPL_PROXY_ENABLED);

boot_define('APP_DB_PORT', $APP_DB_PORT);
boot_define('APP_DB_HOST', $APP_DB_HOST);
boot_define('APP_DB_NAME', $APP_DB_NAME);
boot_define('APP_DB_USER', $APP_DB_USER);
boot_define('APP_DB_PASSWORD', $APP_DB_PASSWORD);

boot_define('APP_DB_TESTS_NAME', $APP_DB_TESTS_NAME);
boot_define('APP_DB_TESTS_PORT', $APP_DB_TESTS_PORT);
boot_define('APP_DB_TESTS_USER', $APP_DB_TESTS_USER);
boot_define('APP_DB_TESTS_PASSWORD', $APP_DB_TESTS_PASSWORD);
boot_define('APP_DB_TESTS_HOST', $APP_DB_TESTS_HOST);

boot_define('APP_SIMULATE_SESSION', $APP_SIMULATE_SESSION);
boot_define('APP_JAVASCRIPT_MINIFIED', $APP_JAVASCRIPT_MINIFIED);

/**
 * Detects the environment on which the application is
 * running, and returns an environment ID string.
 *
 * @return Application_Environments_Environment
 */
function config_detect_environment(): Application_Environments_Environment
{
    $environments = Application_Environments::getInstance();

    $environments->registerDev(ENVIRONMENT_TEST_SUITES)
        ->requireLocalTest();

    $local = $environments->registerDev('local')
        ->requireWindows()
        ->requireCLI();

    // Add the local development hosts to the detection
    $localHosts = config_get_dev_hosts();
    foreach ($localHosts as $host) {
        $local->or()->requireHostNameContains($host);
    }

    $environments->registerProd(ENVIRONMENT_PRODUCTION)
        ->requireHostNameContains('nms-tracker.com');

    return $environments->detect(ENVIRONMENT_PRODUCTION);
}

/**
 * @return string[]
 */
function config_get_dev_hosts(): array
{
    $hostsFile = __DIR__ . '/dev-hosts.txt';

    if (!file_exists($hostsFile)) {
        return array();
    }

    $hosts = explode("\n", file_get_contents($hostsFile));
    $hosts = array_map('trim', $hosts);

    $result = array();

    foreach ($hosts as $host) {
        if (!empty($host)) {
            $result[] = $host;
        }
    }

    return $result;
}
