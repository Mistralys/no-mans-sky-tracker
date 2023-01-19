<?php

declare(strict_types=1);

namespace NMSTracker\Outposts;

use Application_Exception;
use Application_FilterCriteria_Database_CustomColumn;
use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\Area\SolarSystemsScreen;
use NMSTracker\OutpostRoles\OutpostRoleRecord;
use NMSTracker\OutpostsCollection;
use NMSTracker\OutpostServices\OutpostServiceRecord;
use NMSTracker\OutpostServicesCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\SentinelLevels\SentinelLevelRecord;
use NMSTracker\SentinelLevelsCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;

/**
 * @method OutpostRecord[] getItemsObjects()
 */
class OutpostFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_PLANETS = 'planets';
    public const CUSTOM_COL_SERVICE_COUNT = 'custom_service_count';
    public const FILTER_SOLAR_SYSTEMS = 'solar_systems';
    public const FILTER_ROLES = 'roles';
    public const FILTER_SERVICES = 'services';
    public const FILTER_SENTINEL_LEVELS = 'sentinels';

    public function withServiceCounts() : self
    {
        return $this->addSelectColumn($this->getColServicesCount()->getPrimarySelectValue());
    }

    public function getContainer() : OutpostsContainer
    {
        return OutpostsContainer::create($this);
    }

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_outposts}.{outpost_label}');
    }

    public function getColServicesCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_SERVICE_COUNT);
    }

    // region: Selecting filters

    /**
     * @param SolarSystemRecord $solarSystem
     * @return $this
     * @throws Application_Exception
     */
    public function selectSolarSystem(SolarSystemRecord $solarSystem) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SOLAR_SYSTEMS, $solarSystem->getID());
    }

    /**
     * @param OutpostRoleRecord $role
     * @return $this
     * @throws Application_Exception
     */
    public function selectRole(OutpostRoleRecord $role) : self
    {
        return $this->selectCriteriaValue(self::FILTER_ROLES, $role->getID());
    }

    public function selectService(OutpostServiceRecord $service) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SERVICES, $service->getID());
    }

    public function selectResource(ResourceRecord $resource) : self
    {
        $planets = $resource->getPlanetFilters()->getItemsObjects();

        // No planets? Then we have to force the filter
        // to not find any planets, otherwise the filter
        // will have no effect.
        if(empty($planets)) {
            return $this->selectCriteriaValue(self::FILTER_PLANETS, '-999');
        }

        foreach($planets as $planet)
        {
            $this->selectPlanet($planet);
        }

        return $this;
    }

    /**
     * @param PlanetRecord $planet
     * @return $this
     * @throws Application_Exception
     */
    public function selectPlanet(PlanetRecord $planet) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_PLANETS,
            $planet->getID()
        );
    }

    public function selectSentinelLevel(SentinelLevelRecord $level) : self
    {
        return $this->selectCriteriaValue(self::FILTER_SENTINEL_LEVELS, $level->getID());
    }

    // endregion

    public function getColPlanetID() : string
    {
        return (string)$this->statement('{planets}.{planet_primary}');
    }

    protected function prepareQuery() : void
    {
        $this->addJoinStatement("
            JOIN
                {table_planets} AS {planets}
            ON
                {planets}.{planet_primary} = {table_outposts}.{planet_primary}
        ");

        $this->configurePlanets();
        $this->configureRoles();
        $this->configureSolarSystems();
        $this->configureServices();
        $this->configureSentinelLevels();
    }

    private function configureServices() : void
    {
        $serviceIDs = $this->getCriteriaValues(self::FILTER_SERVICES);

        if(empty($serviceIDs))
        {
            return;
        }

        // To ensure we find all entries that have all the selected
        // service IDs, we must look up the services for each service
        // individually, and connect the where statements.

        $parts = array();
        foreach($serviceIDs as $serviceID)
        {
            $parts[] = $this->statement(sprintf("
                {table_outposts}.{outpost_primary}
                IN
                (
                    SELECT
                        {table_services}.{outpost_primary}
                    FROM
                        {table_services}
                    WHERE
                        {table_services}.{service_primary} = %s
                )",
                $serviceID
            ));
        }

        $this->addWhere('('.implode(' AND ', $parts).')');
    }

    protected function _initCustomColumns() : void
    {
        $this->addGroupByStatement('{table_outposts}.{outpost_primary}');

        $this->registerCustomSelect(
            "(
            SELECT 
                COUNT({outpost_primary})
            FROM 
                {table_services}
            WHERE
                {table_services}.{outpost_primary}={table_outposts}.{outpost_primary}
            )",
            self::CUSTOM_COL_SERVICE_COUNT
        );
    }

    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_outposts}', OutpostsCollection::TABLE_NAME)
            ->table('{table_planets}', PlanetsCollection::TABLE_NAME)
            ->table('{table_services}', OutpostsCollection::TABLE_SERVICES)

            ->alias('{planets}', 'alias_planets')

            ->field('{outpost_primary}', OutpostsCollection::PRIMARY_NAME)
            ->field('{service_primary}', OutpostServicesCollection::PRIMARY_NAME)
            ->field('{outpost_label}', OutpostsCollection::COL_LABEL)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME)
            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME)
            ->field('{sentinel_primary}', SentinelLevelsCollection::PRIMARY_NAME);
    }

    /**
     * @return void
     * @throws Application_Exception
     */
    protected function configureSolarSystems() : void
    {
        $this->addWhereColumnIN(
            (string)$this->statement("{planets}.{system_primary}"),
            $this->getCriteriaValues(self::FILTER_SOLAR_SYSTEMS)
        );
    }

    /**
     * @return void
     * @throws Application_Exception
     */
    protected function configureRoles() : void
    {
        $this->addWhereColumnIN(
            OutpostsCollection::COL_ROLE_ID,
            $this->getCriteriaValues(self::FILTER_ROLES)
        );
    }

    /**
     * @return void
     * @throws Application_Exception
     */
    protected function configurePlanets() : void
    {
        $this->addWhereColumnIN(
            $this->getColPlanetID(),
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );
    }

    private function configureSentinelLevels() : void
    {
        $this->addWhereColumnIN(
            (string)$this->statement("{planets}.{sentinel_primary}"),
            $this->getCriteriaValues(self::FILTER_SENTINEL_LEVELS)
        );
    }
}
