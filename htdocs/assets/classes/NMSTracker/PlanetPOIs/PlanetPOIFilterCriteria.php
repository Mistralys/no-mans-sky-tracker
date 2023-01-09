<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetPOIsCollection;

class PlanetPOIFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const DEFAULT_SORT_KEY = PlanetPOIsCollection::COL_LABEL;

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_pois}.{poi_label}');
    }

    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_pois}', PlanetPOIsCollection::TABLE_NAME)

            ->field('{poi_primary}', PlanetPOIsCollection::PRIMARY_NAME)
            ->field('{poi_label}', PlanetPOIsCollection::COL_LABEL);
    }
}
