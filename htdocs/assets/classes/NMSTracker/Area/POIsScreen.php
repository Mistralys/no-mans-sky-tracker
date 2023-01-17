<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker;
use NMSTracker\Area\POIsScreen\POIsListScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class POIsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'pois';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultMode() : string
    {
        return POIsListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPOIs();
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

    public function getNavigationTitle() : string
    {
        return t('POIs');
    }

    public function getTitle() : string
    {
        return t('Points of interest');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->pointsOfInterest());
    }
}
