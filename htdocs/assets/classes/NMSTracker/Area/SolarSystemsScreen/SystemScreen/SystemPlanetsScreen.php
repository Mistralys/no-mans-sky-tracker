<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetListScreen;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker_User;

/**
 * @property PlanetFilterCriteria $filters
 * @property NMSTracker_User $user
 */
class SystemPlanetsScreen
    extends Application_Admin_Area_Mode_Submode
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'planets';

    public function getDefaultAction() : string
    {
        return PlanetListScreen::URL_NAME;
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPlanets();
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
