<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker\SpaceStationsCollection;

class SpaceStationFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_RESOURCES = 'resources';
    public const JOIN_STATION_RESOURCES = 'join_station_resources';

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_stations}.{station_label}');
    }

    // region: A - Select filters

    public function selectResource(ResourceRecord $resource) : self
    {
        return $this->selectCriteriaValue(self::FILTER_RESOURCES, $resource->getID());
    }

    // endregion

    // region: B - Applying filters

    protected function prepareQuery() : void
    {
        $this->applyFilterResources();
    }

    private function applyFilterResources() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_RESOURCES))
        {
            return;
        }

        $this->requireJoin(self::JOIN_STATION_RESOURCES);

        $this->addWhereColumnIN(
            $this->statement('{table_station_resources}.{resource_primary}'),
            $this->getCriteriaValues(self::FILTER_RESOURCES)
        );
    }

    // endregion

    // region: X - Setup

    protected function _registerJoins() : void
    {
        $this->registerJoin(
            self::JOIN_STATION_RESOURCES,
            $this->statement("
                LEFT JOIN
                    {table_station_resources}
                ON
                    {table_station_resources}.{station_primary}={table_stations}.{station_primary}
            ")
        );
    }

    public static function getStatementValues(DBHelper_StatementBuilder_ValuesContainer $container=null) : DBHelper_StatementBuilder_ValuesContainer
    {
        if($container === null)
        {
            $container = statementValues();
        }

        $container
            ->table('{table_stations}', SpaceStationsCollection::TABLE_NAME)
            ->table('{table_station_resources}', SpaceStationsCollection::TABLE_RESOURCES)
            ->table('{table_resources}', ResourcesCollection::TABLE_NAME)

            ->field('{station_label}', SpaceStationsCollection::COL_LABEL)
            ->field('{station_primary}', SpaceStationsCollection::PRIMARY_NAME)
            ->field('{resource_primary}', ResourcesCollection::PRIMARY_NAME)
            ->field('{station_offer_type}', SpaceStationsCollection::COL_RESOURCE_OFFER_TYPE);

        return $container;
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        self::getStatementValues($container);
    }

    // endregion
}
