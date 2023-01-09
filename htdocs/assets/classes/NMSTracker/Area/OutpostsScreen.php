<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\OutpostsScreen\OutpostsListsScreen;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class OutpostsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'outposts';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultMode() : string
    {
        return OutpostsListsScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewOutposts();
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
        return t('Outposts');
    }

    public function getTitle() : string
    {
        return t('Outposts');
    }
}
