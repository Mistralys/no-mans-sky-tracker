<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\Planets\PlanetRecord;

class PlanetPOIFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const DEFAULT_SORT_KEY = PlanetPOIsCollection::COL_LABEL;
    public const FILTER_PLANETS = 'planets';

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_pois}.{poi_label}');
    }

    protected function prepareQuery() : void
    {
        $this->applyPlanets();
    }

    private function applyPlanets() : void
    {
        $this->addWhereColumnIN(
            PlanetPOIsCollection::COL_PLANET_ID,
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );
    }

    public function selectPlanet(PlanetRecord $planet) : self
    {
        return $this->selectCriteriaValue(self::FILTER_PLANETS, $planet->getID());
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
