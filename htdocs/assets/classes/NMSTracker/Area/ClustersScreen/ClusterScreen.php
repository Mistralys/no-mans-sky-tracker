<?php

declare(strict_types=1);

namespace NMSTracker\Area\ClustersScreen;

use Application_Admin_Area_Mode;
use NMSTracker;
use NMSTracker\Area\ClustersScreen\ClusterScreen\ClusterStatusScreen;
use NMSTracker\Interfaces\ViewClusterScreenInterface;
use NMSTracker\Interfaces\ViewClusterScreenTrait;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class ClusterScreen extends Application_Admin_Area_Mode
    implements ViewClusterScreenInterface
{
    use ViewClusterScreenTrait;

    public const URL_NAME = 'cluster';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }


    public function getDefaultSubmode() : string
    {
        return ClusterStatusScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewClusters();
    }

    public function getNavigationTitle() : string
    {
        return t('View');
    }

    public function getTitle() : string
    {
        return t('View a cluster');
    }

    protected function _handleHelp() : void
    {
        $cluster = $this->getCluster();

        $this->renderer
            ->getTitle()
            ->setText($cluster->getLabel())
            ->setIcon($cluster->getIcon());
    }

    protected function _handleSubnavigation() : void
    {
        $cluster = $this->getCluster();

        $this->subnav->addURL(
            t('Overview'),
            $cluster->getAdminStatusURL()
        )
            ->setIcon(NMSTracker::icon()->overview());
    }
}
