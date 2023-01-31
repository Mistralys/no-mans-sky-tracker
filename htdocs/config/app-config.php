<?php
/**
 * Application-specific static configuration settings.
 *
 * @package NMSTracker
 * @subpackage Configuration
 */

declare(strict_types=1);

if(!function_exists('boot_define')) {
    die('May not be accessed directly.');
}

boot_define('APP_CLASS_NAME', 'NMSTracker');
boot_define('APP_CONTENT_LOCALES','en_UK,de_DE');
boot_define('APP_UI_LOCALES', 'en_UK,de_DE');
boot_define('APP_REQUEST_LOG_PASSWORD', 'reqlog');
boot_define('APP_COMPANY_NAME', 'Mistralys');
boot_define('APP_COMPANY_HOMEPAGE', 'https://mistralys.eu');
boot_define('APP_DUMMY_EMAIL', 'nmstracker@example.example');
boot_define('APP_SYSTEM_EMAIL', 'nmstracker@example.example');
boot_define('APP_SYSTEM_NAME', 'NMS Tracker');
