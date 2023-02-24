<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use Application_Exception;
use Application_FilterCriteria_Database_CustomColumn;
use AppUtils\ConvertHelper;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\Clusters\ClusterRecord;
use NMSTracker\ClustersCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\Races\RaceRecord;
use NMSTracker\RacesCollection;
use NMSTracker\SolarSystemsCollection;
use NMSTracker\StarTypes\StarTypeRecord;

class SolarSystemFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const CUSTOM_COL_PLANET_COUNT = 'custom_planet_count';
    public const FILTER_RACES = 'races';
    public const FILTER_STAR_TYPES = 'star_types';
    public const FILTER_CLUSTERS = 'clusters';
    public const CUSTOM_COL_DISTANCE_TO_CORE = 'distance_to_core';
    public const JOIN_CLUSTERS = 'join_clusters';
    private ?bool $ownDiscoveries = null;
    private bool $hasWormhole = false;

    /**
     * @param StarTypeRecord $starType
     * @return $this
     * @throws Application_Exception
     */
    public function selectStarType(StarTypeRecord $starType) : self
    {
        return $this->selectCriteriaValue(self::FILTER_STAR_TYPES, $starType->getID());
    }

    public function selectOwnDiscoveries(?bool $mode) : self
    {
        $this->ownDiscoveries = $mode;
        return $this;
    }

    public function selectHasWormhole() : self
    {
        $this->hasWormhole = true;
        return $this;
    }

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            SolarSystemsCollection::COL_RACE_ID,
            $this->getCriteriaValues(self::FILTER_RACES)
        );

        $this->addWhereColumnIN(
            SolarSystemsCollection::COL_STAR_TYPE_ID,
            $this->getCriteriaValues(self::FILTER_STAR_TYPES)
        );

        $this->addWhereColumnIN(
            SolarSystemsCollection::COL_CLUSTER_ID,
            $this->getCriteriaValues(self::FILTER_CLUSTERS)
        );

        if(isset($this->ownDiscoveries))
        {
            $this->addWhereColumnEquals(
                SolarSystemsCollection::COL_IS_OWN_DISCOVERY,
                ConvertHelper::boolStrict2string($this->ownDiscoveries, true)
            );
        }

        if($this->hasWormhole)
        {
            $this->addWhereColumnNOT_NULL(SolarSystemsCollection::COL_WORMHOLE_TO);
        }
    }

    public function withDistanceToCore() : self
    {
        return $this->addSelectColumn($this->getColDistanceToCore()->getPrimarySelectValue());
    }

    public function getColDistanceToCore() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_DISTANCE_TO_CORE);
    }

    public function withPlanetCounts() : self
    {
        return $this->addSelectColumn($this->getColPlanetCount()->getPrimarySelectValue());
    }

    public function getColPlanetCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_PLANET_COUNT);
    }

    /**
     * @param RaceRecord $race
     * @return $this
     * @throws Application_Exception
     */
    public function selectRace(RaceRecord $race) : self
    {
        return $this->selectCriteriaValue(self::FILTER_RACES, $race->getID());
    }

    public function selectCluster(ClusterRecord $record) : self
    {
        return $this->selectCriteriaValue(self::FILTER_CLUSTERS, $record->getID());
    }

    protected function _initCustomColumns() : void
    {
        $this->addGroupByStatement('{system_primary}');

        $this->registerCustomSelect(
            "(
            SELECT 
                COUNT({planet_primary})
            FROM 
                {table_planets}
            WHERE
                {table_planets}.{system_primary}={table_systems}.{system_primary}
            )",
            self::CUSTOM_COL_PLANET_COUNT
        );

        $this->registerCustomSelect(
            "{table_clusters}.{cluster_core_distance}",
            self::CUSTOM_COL_DISTANCE_TO_CORE
        )
            ->requireJoin(self::JOIN_CLUSTERS);
    }

    protected function _registerJoins() : void
    {
        $this->registerJoinStatement(
            self::JOIN_CLUSTERS,
            "
            LEFT JOIN
                {table_clusters}
            ON
                {table_systems}.{cluster_primary}={table_clusters}.{cluster_primary}"
        );
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_systems}', SolarSystemsCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)
            ->table('{table_races}', RacesCollection::TABLE_NAME)
            ->table('{table_clusters}', ClustersCollection::TABLE_NAME)

            ->field('{cluster_core_distance}', ClustersCollection::COL_CORE_DISTANCE)

            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{race_primary}', RacesCollection::PRIMARY_NAME)
            ->field('{cluster_primary}', ClustersCollection::PRIMARY_NAME);
    }
}
