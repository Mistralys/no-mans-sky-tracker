<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use Application_Exception;
use AppUtils\ConvertHelper;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetsCollection;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\RacesCollection;
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

    private ?bool $scanComplete = null;

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_planets}.{planet_label}');
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

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            PlanetsCollection::COL_SOLAR_SYSTEM_ID,
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
    }

    public function selectSolarSystem(SolarSystemRecord $system) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SOLAR_SYSTEMS, $system->getID());
    }

    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_systems}', SolarSystemsCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)
            ->table('{table_races}', RacesCollection::TABLE_NAME)

            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{planet_label}', SolarSystemsCollection::COL_LABEL)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{race_primary}', RacesCollection::PRIMARY_NAME);
    }
}