<?php

declare(strict_types=1);

namespace NMSTracker\Clusters;

use Application_FilterCriteria_Database_CustomColumn;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\ClustersCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;

/**
 * @method ClusterRecord[] getItemsObjects()
 */
class ClusterFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const CUSTOM_COL_SYSTEMS_COUNT = 'systems_count';
    public const JOIN_SOLAR_SYSTEMS = 'join_solar_systems';
    public const JOIN_PLANETS = 'join_planets';
    public const CUSTOM_COL_PLANETS_COUNT = 'col_planets_count';

    protected function prepareQuery() : void
    {
        $this->addGroupByStatement('{table_clusters}.{cluster_primary}');

        if($this->hasJoin(self::JOIN_SOLAR_SYSTEMS)) {
            $this->addGroupByStatement('{table_systems}.{system_primary}');
        }
    }

    public function withSystemsCount() : self
    {
        return $this->withCustomColumn(self::CUSTOM_COL_SYSTEMS_COUNT);
    }

    public function getColSystemsCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_SYSTEMS_COUNT);
    }

    public function withPlanetsCount() : self
    {
        return $this->withCustomColumn(self::CUSTOM_COL_PLANETS_COUNT);
    }

    public function getColPlanetsCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_PLANETS_COUNT);
    }

    protected function _initCustomColumns() : void
    {

        $this->registerCustomSelect(
            "(
            SELECT 
                COUNT({system_primary})
            FROM 
                {table_systems}
            WHERE
                {table_systems}.{cluster_primary}={table_clusters}.{cluster_primary}
            )",
            self::CUSTOM_COL_SYSTEMS_COUNT,
        );

        $this->registerCustomSelect(
            "(
            SELECT
                COUNT({planet_primary})
            FROM
                {table_planets} AS {cluster_planets}
            LEFT JOIN
                {table_systems} AS {planet_systems}
            ON
                {planet_systems}.{system_primary}={cluster_planets}.{system_primary}
            WHERE
                {planet_systems}.{cluster_primary}={table_clusters}.{cluster_primary}
            )",
            self::CUSTOM_COL_PLANETS_COUNT
        )
            ->requireJoin(self::JOIN_SOLAR_SYSTEMS);
    }

    protected function _registerJoins() : void
    {
        $this->registerJoinStatement(
            self::JOIN_SOLAR_SYSTEMS,
            "
            LEFT JOIN
                {table_systems}
            ON
                {table_systems}.{system_primary}={table_clusters}.{cluster_primary}
            "
        );
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_clusters}', ClustersCollection::TABLE_NAME)
            ->table('{table_systems}', SolarSystemsCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)

            ->alias('{cluster_planets}', 'cluster_planets')
            ->alias('{planet_systems}', 'planet_systems')

            ->field('{cluster_primary}', ClustersCollection::PRIMARY_NAME)
            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME);
    }
}
