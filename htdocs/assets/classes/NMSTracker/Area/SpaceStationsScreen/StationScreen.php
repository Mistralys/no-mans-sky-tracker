<?php

declare(strict_types=1);

namespace NMSTracker\Area\SpaceStationsScreen;

use Application_Admin_Area_Mode;
use NMSTracker;
use NMSTracker\Area\SpaceStationsScreen\StationScreen\StationStatusScreen;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenTrait;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class StationScreen
    extends Application_Admin_Area_Mode
    implements ViewSpaceStationScreenInterface
{
    use ViewSpaceStationScreenTrait;

    public const URL_NAME = 'station';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultSubmode() : string
    {
        return StationStatusScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewSpaceStations();
    }

    public function getNavigationTitle() : string
    {
        return t('View');
    }

    public function getTitle() : string
    {
        return t('Space station details');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->spaceStation())
            ->setText($this->getSpaceStation()->getLabel());
    }

    protected function _handleSubnavigation() : void
    {
        $station = $this->getSpaceStation();

        $this->subnav->addURL(t('Status'), $station->getAdminStatusURL())
            ->setIcon(NMSTracker::icon()->status());

        $this->subnav->addURL(t('Settings'), $station->getAdminSettingsURL())
            ->setIcon(NMSTracker::icon()->settings());
    }
}
