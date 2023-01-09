<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\PlanetsScreen\PlanetsListScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class PlanetsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'planets';

    public function getDefaultMode() : string
    {
        return PlanetsListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPlanets();
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
        return t('Planets');
    }

    public function getTitle() : string
    {
        return t('Planets');
    }
}
