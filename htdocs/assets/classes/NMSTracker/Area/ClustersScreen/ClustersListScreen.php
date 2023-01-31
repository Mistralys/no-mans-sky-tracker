<?php

declare(strict_types=1);

namespace NMSTracker\Area\ClustersScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\ClustersScreen;
use NMSTracker\Clusters\ClusterFilterCriteria;
use NMSTracker\Clusters\ClusterRecord;
use NMSTracker\ClassFactory;
use NMSTracker\ClustersCollection;
use NMSTracker\OutpostsCollection;
use NMSTracker_User;

/**
 * @property ClusterFilterCriteria $filters
 * @property NMSTracker_User $user
 * @property ClustersScreen $area
 */
class ClustersListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'clusters-list';
    public const GRID_NAME = 'global-clusters-list';
    public const COL_LABEL = 'label';
    public const COL_CORE_DISTANCE = 'core_distance';
    public const COL_SYSTEMS = 'systems';
    public const COL_PLANETS = 'planets';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getGridName() : string
    {
        return self::GRID_NAME;
    }

    /**
     * @return ClustersCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createClusters();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $cluster = ClassHelper::requireObjectInstanceOf(ClusterRecord::class, $record);

        return array(
            self::COL_LABEL => $cluster->getLabelLinked(),
            self::COL_CORE_DISTANCE => $cluster->getCoreDistancePretty(),
            self::COL_SYSTEMS => $entry->getColumnInt($this->filters->getColSystemsCount()->getName()),
            self::COL_PLANETS => $entry->getColumnInt($this->filters->getColPlanetsCount()->getName())
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, ClustersCollection::COL_LABEL);

        $this->grid->addColumn(self::COL_CORE_DISTANCE, t('Core distance'))
            ->setSortable(false, ClustersCollection::COL_CORE_DISTANCE)
            ->alignRight();

        $this->grid->addColumn(self::COL_SYSTEMS, t('Solar systems'))
            ->setSortable(false, $this->filters->getColSystemsCount()->getSecondarySelectValue())
            ->alignRight();

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'))
            ->setSortable(false, $this->filters->getColPlanetsCount()->getSecondarySelectValue())
            ->alignRight();
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewClusters();
    }

    public function getNavigationTitle() : string
    {
        return t('Clusters');
    }

    public function getTitle() : string
    {
        return t('Global clusters overview');
    }

    protected function configureFilters() : void
    {
        $this->filters->withSystemsCount();
        $this->filters->withPlanetsCount();
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('create_cluster', t('Add a cluster...'))
            ->setIcon(NMSTracker::icon()->add())
            ->link($this->createCollection()->getAdminCreateURL());

        parent::_handleSidebar();
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon($this->createCollection()->getIcon());
    }
}
