<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode;
use Application_Admin_Area_Mode_Submode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystems\SystemResourceResult;
use NMSTracker\SolarSystemsCollection;
use UI_DataGrid;

/**
 * @property ResourceFilterCriteria $filters
 */
class SystemResourcesScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'system-resources';
    public const COL_LABEL = 'label';
    public const COL_PLANETS = 'planets';
    public const COL_OUTPOSTS = 'outposts';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setSubtitle($this->getTitle())
            ->setAbstract(t('These are all resources available on the planets in the system.'));
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $resource = ClassHelper::requireObjectInstanceOf(
            ResourceRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $resource->getLabelLinked(),
            self::COL_PLANETS => $this->renderPlanets($resource),
            self::COL_OUTPOSTS => $this->renderOutposts($resource)
        );
    }

    public function getNavigationTitle() : string
    {
        return t('Resources');
    }

    public function getTitle() : string
    {
        return t('System resources overview');
    }

    private function renderPlanets(ResourceRecord $record) : string
    {
        return $record->getPlanetFilters()
            ->selectSolarSystem($this->getSolarSystem())
            ->getContainer()
            ->createBulletRenderer()
            ->render();
    }

    /**
     * @return ResourcesCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createResources();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectSolarSystem($this->getSolarSystem());
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'));

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'));

        $this->grid->addColumn(self::COL_OUTPOSTS, t('Outposts'));

        $this->grid->addHiddenVar(
            SolarSystemsCollection::PRIMARY_NAME,
            (string)$this->getSolarSystem()->getID()
        );

        $this->filterSettings->addHiddenVar(
            SolarSystemsCollection::PRIMARY_NAME,
            (string)$this->getSolarSystem()->getID()
        );
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminResourcesURL();
    }

    private function renderOutposts(ResourceRecord $resource) : string
    {
        return $resource
            ->getOutpostFilters()
            ->selectSolarSystem($this->getSolarSystem())
            ->getContainer()
            ->createBulletRenderer()
            ->makeWithPlanetName()
            ->render();
    }
}
