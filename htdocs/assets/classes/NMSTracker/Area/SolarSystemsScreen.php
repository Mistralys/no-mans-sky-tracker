<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\SolarSystemsScreen\SystemsListScreen;

class SolarSystemsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'solar-systems';

    public function getDefaultMode() : string
    {
        return SystemsListScreen::URL_NAME;
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
        return t('Solar systems');
    }

    public function getTitle() : string
    {
        return t('Solar systems');
    }
}
