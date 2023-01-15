<?php

declare(strict_types=1);

namespace NMSTracker\PlanetTypes;

use Application_FilterCriteria_Database_CustomColumn;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetsCollection;
use NMSTracker\PlanetTypesCollection;

class PlanetTypeFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const CUSTOM_COL_PLANET_COUNT = 'planet_count';

    protected function _registerJoins() : void
    {
    }

    protected function prepareQuery() : void
    {
        $this->addGroupByStatement("{table_types}.{type_primary}");
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_types}', PlanetTypesCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)

            ->field('{type_primary}', PlanetTypesCollection::PRIMARY_NAME);
    }

    public function getColPlanetCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_PLANET_COUNT);
    }

    public function withPlanetCounts() : self
    {
        return $this->withCustomColumn(self::CUSTOM_COL_PLANET_COUNT);
    }

    protected function _initCustomColumns() : void
    {
        $this->registerCustomColumn(
            self::CUSTOM_COL_PLANET_COUNT,
            $this->statement("
            (
                SELECT
                    COUNT(*)
                FROM
                    {table_planets}
                WHERE
                    {type_primary}={table_types}.{type_primary}
            )            
            ")
        );
    }
}

