<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\PlanetsCollection;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;

/**
 * @method ResourceRecord[] getItemsObjects()
 */
class ResourceFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_INCLUDE_IDS = 'include_ids';
    public const FILTER_SOLAR_SYSTEMS = 'solar_systems';
    public const JOIN_PLANETS_RESOURCES = 'planets_resources';
    public const FILTER_PLANETS = 'planets';
    public const FILTER_RESOURCE_TYPES = 'resource_types';

    public function selectType(BaseResourceType $resourceType) : self
    {
        return $this->selectCriteriaValue(self::FILTER_RESOURCE_TYPES, $resourceType->getID());
    }

    protected function getCountColumn() : string
    {
        return $this->getColID();
    }

    protected function prepareQuery() : void
    {
        $this->makeDistinct();

        $this->addWhereColumnIN(
            $this->statement('{table_resources}.{resource_primary}'),
            $this->getCriteriaValues(self::FILTER_INCLUDE_IDS)
        );

        $this->addWhereColumnIN(
            $this->statement('{table_resources}.{field_type}'),
            $this->getCriteriaValues(self::FILTER_RESOURCE_TYPES)
        );

        $this->requireJoin(self::JOIN_PLANETS_RESOURCES);

        $this->addWhereColumnIN(
            $this->statement('{table_planet_resources}.{system_primary}'),
            $this->getCriteriaValues(self::FILTER_SOLAR_SYSTEMS)
        );

        $this->addWhereColumnIN(
            $this->statement('{table_planet_resources}.{planet_primary}'),
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );

        $this->addGroupByStatement('{table_resources}.{resource_primary}');
    }

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_resources}.{field_label}');
    }

    public function getColID() : string
    {
        return (string)$this->statement('{table_resources}.{resource_primary}');
    }

    protected function _registerJoins() : void
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

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_resources}', ResourcesCollection::TABLE_NAME)
            ->table('{table_planet_resources}', PlanetsCollection::TABLE_RESOURCES)

            ->field('{resource_primary}', ResourcesCollection::PRIMARY_NAME)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{field_label}', ResourcesCollection::COL_LABEL)
            ->field('{field_type}', ResourcesCollection::COL_TYPE);
    }

    public function includeID(int $id) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_INCLUDE_IDS,
            $id
        );
    }

    /**
     * @param int[] $ids
     * @return $this
     */
    public function includeIDs(array $ids, bool $allowEmpty) : self
    {
        if(empty($ids) && !$allowEmpty) {
            $ids = array(PHP_INT_MIN);
        }

        foreach($ids as $id)
        {
            $this->includeID($id);
        }

        return $this;
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

    public function selectOutpost(OutpostRecord $outpost) : self
    {
        return $this->selectPlanet($outpost->getPlanet());
    }

    public function getContainer() : ResourceContainer
    {
        return ResourceContainer::create($this);
    }
}
