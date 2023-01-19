<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use Application_Exception;
use NMSTracker\ClassFactory;
use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetsCollection;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker\SpaceStations\BaseStationOfferType;
use NMSTracker\SpaceStations\SpaceStationRecord;
use NMSTracker\SpaceStationsCollection;

/**
 * @method ResourceRecord[] getItemsObjects()
 */
class ResourceFilterCriteria extends DBHelper_BaseFilterCriteria
{
    // region: C - Applying filters

    protected function prepareQuery() : void
    {
        $this->makeDistinct();

        $this->applyFilterResourceTypes();
        $this->applyFilterSolarSystems();
        $this->applyFilterPlanets();
        $this->applyFilterSpaceStations();
        $this->applyFilterStationOfferTypes();

        $this->addGroupByStatement('{table_resources}.{resource_primary}');
    }

    private function applyFilterSpaceStations() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_SPACE_STATIONS))
        {
            return;
        }

        $this->requireJoin(self::JOIN_SPACE_STATIONS);

        $this->addWhereColumnIN(
            $this->statement('{table_station_resources}.{station_primary}'),
            $this->getCriteriaValues(self::FILTER_SPACE_STATIONS)
        );
    }

    private function applyFilterStationOfferTypes() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_STATION_OFFER_TYPES))
        {
            return;
        }

        $this->requireJoin(self::JOIN_SPACE_STATIONS);

        $this->addWhereColumnIN(
            $this->statement('{table_station_resources}.{station_offer_type}'),
            $this->getCriteriaValues(self::FILTER_STATION_OFFER_TYPES)
        );
    }

    private function applyFilterSolarSystems() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_SOLAR_SYSTEMS))
        {
            return;
        }

        $this->requireJoin(self::JOIN_PLANETS_RESOURCES);

        $this->addWhereColumnIN(
            $this->statement('{table_planet_resources}.{system_primary}'),
            $this->getCriteriaValues(self::FILTER_SOLAR_SYSTEMS)
        );
    }

    private function applyFilterPlanets() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_PLANETS))
        {
            return;
        }

        $this->requireJoin(self::JOIN_PLANETS_RESOURCES);

        $this->addWhereColumnIN(
            $this->statement('{table_planet_resources}.{planet_primary}'),
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );
    }

    // endregion

    // region: X - Configuration

    public const JOIN_PLANETS_RESOURCES = 'planets_resources';
    public const JOIN_SPACE_STATIONS = 'join_space_stations';
    public const FILTER_SOLAR_SYSTEMS = 'solar_systems';
    public const FILTER_PLANETS = 'planets';
    public const FILTER_RESOURCE_TYPES = 'resource_types';
    public const FILTER_SPACE_STATIONS = 'space_stations';
    public const FILTER_STATION_OFFER_TYPES = 'station_offer_type';

    protected function _registerJoins() : void
    {
        $this->registerJoinPlanetResources();
        $this->registerJoinStationResources();
    }

    private function registerJoinPlanetResources() : void
    {
        $this->registerJoin(
            self::JOIN_PLANETS_RESOURCES,
            $this->statement("
                LEFT JOIN
                    {table_planet_resources}
                ON
                    {table_planet_resources}.{resource_primary}={table_resources}.{resource_primary}
             ")
        );
    }

    private function registerJoinStationResources() : void
    {
        $this->registerJoin(
            self::JOIN_SPACE_STATIONS,
            $this->statement("
                LEFT JOIN
                    {table_station_resources}
                ON
                    {table_station_resources}.{resource_primary}={table_resources}.{resource_primary}
            ")
        );
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_resources}', ResourcesCollection::TABLE_NAME)
            ->table('{table_planet_resources}', PlanetsCollection::TABLE_RESOURCES)
            ->table('{table_station_resources}', SpaceStationsCollection::TABLE_RESOURCES)

            ->field('{resource_primary}', ResourcesCollection::PRIMARY_NAME)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{station_primary}', SpaceStationsCollection::PRIMARY_NAME)
            ->field('{station_offer_type}', SpaceStationsCollection::COL_RESOURCE_OFFER_TYPE)
            ->field('{field_label}', ResourcesCollection::COL_LABEL)
            ->field('{field_type}', ResourcesCollection::COL_TYPE);
    }

    // endregion

    // region: A - Selecting filters

    public function selectSpaceStation(SpaceStationRecord $station) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_SPACE_STATIONS,
            $station->getID()
        );
    }

    public function selectStationOfferType(BaseStationOfferType $type) : self
    {
        return $this->selectCriteriaValue(self::FILTER_STATION_OFFER_TYPES, $type->getID());
    }

    public function selectSolarSystem(SolarSystemRecord $solarSystem) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_SOLAR_SYSTEMS,
            $solarSystem->getID()
        );
    }

    public function selectPlanet(PlanetRecord $planet) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_PLANETS,
            $planet->getID()
        );
    }

    public function selectResourceType(BaseResourceType $resourceType) : self
    {
        return $this->selectCriteriaValue(self::FILTER_RESOURCE_TYPES, $resourceType->getID());
    }

    public function selectOutpost(OutpostRecord $outpost) : self
    {
        return $this->selectPlanet($outpost->getPlanet());
    }

    public function selectTradeCommodities() : self
    {
        return $this->selectResourceType(
            ClassFactory::createResourceTypes()
                ->getByID(ResourceTypesCollection::TYPE_TRADEABLE)
        );
    }

    // endregion

    // region: B - Utility methods

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_resources}.{field_label}');
    }

    public function getColID() : string
    {
        return (string)$this->statement('{table_resources}.{resource_primary}');
    }

    protected function getCountColumn() : string
    {
        return $this->getColID();
    }

    public function getContainer() : ResourceContainer
    {
        return ResourceContainer::create($this);
    }

    // endregion

    /**
     * @return void
     * @throws Application_Exception
     */
    protected function applyFilterResourceTypes() : void
    {
        $this->addWhereColumnIN(
            $this->statement('{table_resources}.{field_type}'),
            $this->getCriteriaValues(self::FILTER_RESOURCE_TYPES)
        );
    }
}
