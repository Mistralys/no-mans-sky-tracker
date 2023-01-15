<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypesListScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class PlanetTypesScreen extends Application_Admin_Area
{
    public const URL_NAME = 'planet-types';

    public function getDefaultMode() : string
    {
        return PlanetTypesListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return t('Manage');
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPlanetTypes();
    }

    public function getDependencies() : array
    {
        return array(
            PlanetsScreen::URL_NAME
        );
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
        return t('Planet types');
    }

    public function getTitle() : string
    {
        return t('Planet types');
    }
}
