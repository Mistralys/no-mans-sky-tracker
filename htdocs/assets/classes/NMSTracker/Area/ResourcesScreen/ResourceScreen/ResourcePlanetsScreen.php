<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen\ResourceScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use AppUtils\ClassHelper;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewResourceScreenInterface;
use NMSTracker\Interfaces\Admin\ViewResourceScreenTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\Planets\PlanetFilterSettings;
use NMSTracker\ResourcesCollection;

/**
 * @property PlanetFilterCriteria $filters
 * @property PlanetFilterSettings $filterSettings
 */
class ResourcePlanetsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewResourceScreenInterface
{
    use ViewResourceScreenTrait;

    public const URL_NAME = 'planets';

    public const COL_LABEL = 'label';
    public const COL_SENTINELS = 'sentinels';
    public const COL_TYPE = 'type';
    public const COL_SYSTEM = 'system';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $planet = ClassHelper::requireObjectInstanceOf(
            PlanetRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $planet->getLabelLinked(),
            self::COL_SYSTEM => $planet->getSolarSystem()->getLabelLinked(),
            self::COL_SENTINELS => $planet->getSentinelLevel()->getLabelLinked(),
            self::COL_TYPE => $planet->getType()->getLabelLinked()
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SYSTEM, t('Solar system'));
        $this->grid->addColumn(self::COL_TYPE, t('Type'));

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));
    }

    protected function configureActions() : void
    {
    }

    protected function configureFilters() : void
    {
        $resource = $this->getResource();

        $this->filters->selectResource($resource);

        $this->filterSettings->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
        $this->grid->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
    }

    public function getBackOrCancelURL() : string
    {
        return ClassFactory::createResources()->getAdminListURL();
    }

    public function getNavigationTitle() : string
    {
        return t('Planets');
    }

    public function getTitle() : string
    {
        return t('Resource planets');
    }
}