<?php

declare(strict_types=1);

namespace NMSTracker\Area\ClustersScreen\ClusterScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Interfaces\ViewClusterScreenInterface;
use NMSTracker\Interfaces\ViewClusterScreenTrait;

class ClusterStatusScreen extends Application_Admin_Area_Mode_Submode
    implements ViewClusterScreenInterface
{
    use ViewClusterScreenTrait;

    public const URL_NAME = 'status';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Cluster overview');
    }

    public function getTitle() : string
    {
        return t('Solar system cluster overview');
    }

    public function getDefaultAction() : string
    {
        return '';
    }

    protected function _renderContent()
    {
        $grid = $this->ui->createPropertiesGrid();
        $cluster = $this->getCluster();

        $grid->add(t('Core distance'), number_format($cluster->getCoreDistance(), 0, '.', ' '))
            ->setComment(t('Light years to the galaxy core'));

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }
}
