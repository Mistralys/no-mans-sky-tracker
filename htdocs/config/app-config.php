<?php
/**
 * Application-specific static configuration settings.
 *
 * @package NMSTracker
 * @subpackage Configuration
 */

declare(strict_types=1);

use Application\Environments;
use AppUtils\FileHelper\FolderInfo;
use NMSTracker\EnvironmentsConfig;

if(!function_exists('boot_define')) {
    die('May not be accessed directly.');
}

try
{
    (new EnvironmentsConfig(FolderInfo::factory(__DIR__)))
        ->detect();
}
catch (Throwable $e)
{
    Environments::displayException($e);
}
