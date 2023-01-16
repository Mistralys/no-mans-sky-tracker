<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\Resources\ResourceFilterSettings;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property ResourceFilterCriteria $filters
 * @property ResourceFilterSettings $filterSettings
 */
class ResourcesListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';
    public const COL_LABEL = 'label';
    public const GRID_NAME = 'global-resources';
    public const COL_PLANETS = 'planets';
    public const COL_TYPE = 'type';
    public const COL_OUTPOSTS = 'outposts';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return true;
    }

    public function getGridName() : string
    {
        return self::GRID_NAME;
    }

    /**
     * @return ResourcesCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createResources();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $resource = ClassHelper::requireObjectInstanceOf(
            ResourceRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $resource->getLabelLinked(),
            self::COL_TYPE => $resource->getType()->getLabel(),
            self::COL_PLANETS => sb()->link(
                (string)$resource->getPlanetFilters()->countItems(),
                $resource->getAdminPlanetsURL()
            ),
            self::COL_OUTPOSTS => sb()->link(
                (string)$resource->getOutpostFilters()->countItems(),
                $resource->getAdminOutpostsURL()
            )
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_TYPE, t('Type'));

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'));

        $this->grid->addColumn(self::COL_OUTPOSTS, t('Outposts'));
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function getNavigationTitle() : string
    {
        return t('Resources');
    }

    public function getTitle() : string
    {
        return t('Global resources list');
    }
}
