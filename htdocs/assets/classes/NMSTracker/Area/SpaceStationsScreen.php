<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\SpaceStationsScreen\StationsListScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class SpaceStationsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'space-stations';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultMode() : string
    {
        return StationsListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewSpaceStations();
    }

    public function getDependencies() : array
    {
        return array();
    }

    public function isCore() : bool
    {
        return true;
    }

    public function getNavigationTitle() : string
    {
        return t('Space stations');
    }

    public function getTitle() : string
    {
        return t('Space stations');
    }
}
