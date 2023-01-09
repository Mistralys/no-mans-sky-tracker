<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetPOIs\PlanetPOIFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;

/**
 * @property PlanetPOIFilterCriteria $filters
 */
class PlanetPOIsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements ViewPlanetScreenInterface
{
    use ViewPlanetScreenTrait;
    use ViewSystemScreenTrait;

    public const URL_NAME = 'planet-pois';
    public const COL_LABEL = 'label';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    public function getAbstract() : string
    {
        return t(
            'See the article regarding %1$s to know how to use the coordinates.',
            sb()->link(t('planetary coordinates'), 'https://nomanssky.fandom.com/wiki/Planetary_coordinates', true)
        );
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof PlanetPOIRecord)
        {
            return array(
                self::COL_LABEL => $record->getLabel()
            );
        }

        return array();
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());
    }

    protected function configureActions() : void
    {
        $this->grid->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, $this->getSolarSystem()->getID());
        $this->grid->addHiddenVar(PlanetsCollection::PRIMARY_NAME, $this->getPlanet()->getID());
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminStatusURL();
    }

    public function getNavigationTitle() : string
    {
        return t('POIs');
    }

    public function getTitle() : string
    {
        return t('Points of interest');
    }
}
