<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\ResourcesScreen\ResourcesListScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class ResourcesScreen extends Application_Admin_Area
{
    public const URL_NAME = 'resources';

    public function getDefaultMode() : string
    {
        return ResourcesListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return true;
    }

    public function getDependencies() : array
    {
        return array();
    }

    public function isCore() : bool
    {
        return true;
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Resources');
    }

    public function getTitle() : string
    {
        return t('Global resources');
    }
}
