<?php
/**
 * Main configuration file.
 *
 * @package NMSTracker
 * @subpackage Configuration
 */

if(!file_exists(__DIR__.'/../config.php')) {
    die('Configuration file is not present.');
}

require_once __DIR__.'/../config.php';

boot_define('APP_URL', NMS_TRACKER_URL);
boot_define('APP_INSTANCE_ID', 'main');
boot_define('APP_APPSET', 'main');
boot_define('APP_LOGGING_ENABLED', true);
boot_define('APP_DEEPL_PROXY_ENABLED', false);
boot_define('APP_SIMULATE_SESSION', true);
boot_define('APP_JAVASCRIPT_MINIFIED', false);

boot_define('APP_DB_PORT', NMS_TRACKER_DB_PORT);
boot_define('APP_DB_HOST', NMS_TRACKER_DB_HOST);
boot_define('APP_DB_NAME', NMS_TRACKER_DB_NAME);
boot_define('APP_DB_USER', NMS_TRACKER_DB_USER);
boot_define('APP_DB_PASSWORD', NMS_TRACKER_DB_PASSWORD);

boot_define('APP_DB_TESTS_NAME', NMS_TRACKER_TEST_DB_NAME);
boot_define('APP_DB_TESTS_PORT', NMS_TRACKER_TEST_DB_PORT);
boot_define('APP_DB_TESTS_USER', NMS_TRACKER_TEST_DB_USER);
boot_define('APP_DB_TESTS_PASSWORD', NMS_TRACKER_TEST_DB_PASSWORD);
boot_define('APP_DB_TESTS_HOST', NMS_TRACKER_TEST_DB_HOST);
