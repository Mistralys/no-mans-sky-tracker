<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker;
use NMSTracker\Area\TagsScreen\TagsListScreen;
use NMSTracker_User;
use UI_Icon;

/**
 * @property NMSTracker_User $user
 */
class TagsScreen extends Application_Admin_Area
{
    public const URL_NAME = 'tags';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultMode() : string
    {
        return TagsListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return t('Manage');
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewTags();
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
        return t('Tags');
    }

    public function getTitle() : string
    {
        return t('Tags');
    }

    public function getNavigationIcon() : ?UI_Icon
    {
        return NMSTracker::icon()->tags();
    }
}
