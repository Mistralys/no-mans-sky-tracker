<?php

declare(strict_types=1);

namespace NMSTracker\Area;

use Application_Admin_Area;
use NMSTracker\Area\ClustersScreen\ClustersListScreen;
use NMSTracker\ClassFactory;
use NMSTracker_User;
use UI_Icon;

/**
 * @property NMSTracker_User $user
 */
class ClustersScreen extends Application_Admin_Area
{
    public const URL_NAME = 'clusters';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultMode() : string
    {
        return ClustersListScreen::URL_NAME;
    }

    public function getNavigationGroup() : string
    {
        return t('Manage');
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewClusters();
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
        return t('Clusters');
    }

    public function getNavigationIcon() : ?UI_Icon
    {
        return ClassFactory::createClusters()->getIcon();
    }

    public function getTitle() : string
    {
        return t('Clusters');
    }
}
