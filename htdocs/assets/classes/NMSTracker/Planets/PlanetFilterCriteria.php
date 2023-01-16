<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use Application_Exception;
use AppUtils\ConvertHelper;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetsCollection;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\RacesCollection;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker\SentinelLevels\SentinelLevelRecord;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;


/**
 * @method PlanetRecord[] getItemsObjects()
 */
class PlanetFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_SOLAR_SYSTEMS = 'solar_systems';
    public const FILTER_SENTINEL_LEVELS = 'sentinel_levels';
    public const FILTER_PLANET_TYPES = 'planet_types';
    public const FILTER_RESOURCES = 'resources';
    public const JOIN_RESOURCES = 'join_resources';

    private ?bool $scanComplete = null;

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_planets}.{planet_label}');
    }

    public function getColID() : string
    {
        return (string)$this->statement('{table_planets}.{system_primary}');
    }

    /**
     * @param bool|null $value
     * @return $this
     */
    public function selectScanComplete(?bool $value) : self
    {
        $this->scanComplete = $value;
        return $this;
    }

    /**
     * @param SentinelLevelRecord $sentinelLevel
     * @return $this
     * @throws Application_Exception
     */
    public function selectSentinelLevel(SentinelLevelRecord $sentinelLevel) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SENTINEL_LEVELS, $sentinelLevel->getID());
    }

    /**
     * @param PlanetTypeRecord $planetType
     * @return $this
     * @throws Application_Exception
     */
    public function selectPlanetType(PlanetTypeRecord $planetType) : self
    {
        return $this->selectCriteriaValue(self::FILTER_PLANET_TYPES, $planetType->getID());
    }

    public function selectResource(ResourceRecord $resource) : self
    {
        return $this->selectCriteriaValue(self::FILTER_RESOURCES, $resource->getID());
    }

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            $this->getColID(),
            $this->getCriteriaValues(self::FILTER_SOLAR_SYSTEMS)
        );

        $this->addWhereColumnIN(
            PlanetsCollection::COL_SENTINEL_LEVEL_ID,
            $this->getCriteriaValues(self::FILTER_SENTINEL_LEVELS)
        );

        $this->addWhereColumnIN(
            PlanetsCollection::COL_PLANET_TYPE_ID,
            $this->getCriteriaValues(self::FILTER_PLANET_TYPES)
        );

        if($this->scanComplete !== null) {
            $this->addWhereColumnEquals(
                PlanetsCollection::COL_SCAN_COMPLETE,
                ConvertHelper::boolStrict2string($this->scanComplete, true)
            );
        }
        
        $resourceIDs = $this->getCriteriaValues(self::FILTER_RESOURCES);
        if(!empty($resourceIDs))
        {
            $this->requireJoin(self::JOIN_RESOURCES);
            
            $this->addWhereColumnIN(
                $this->statement('{table_resources}.{resource_primary}'),
                $resourceIDs
            );
        }
    }

    public function selectSolarSystem(SolarSystemRecord $system) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SOLAR_SYSTEMS, $system->getID());
    }

    public function getContainer() : PlanetsContainer
    {
        return PlanetsContainer::create($this);
    }

    protected function _registerJoins() : void
    {
        $this->registerJoin(
            self::JOIN_RESOURCES,
            $this->statement("
                LEFT JOIN
                    {table_resources}
                ON
                    {table_resources}.{planet_primary}={table_planets}.{planet_primary}
            ")
        );
    }

    protected function _initCustomColumns() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_systems}', SolarSystemsCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)
            ->table('{table_races}', RacesCollection::TABLE_NAME)
            ->table('{table_resources}', PlanetsCollection::TABLE_RESOURCES)

            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{planet_label}', SolarSystemsCollection::COL_LABEL)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{race_primary}', RacesCollection::PRIMARY_NAME)
            ->field('{resource_primary}', ResourcesCollection::PRIMARY_NAME);
    }
}