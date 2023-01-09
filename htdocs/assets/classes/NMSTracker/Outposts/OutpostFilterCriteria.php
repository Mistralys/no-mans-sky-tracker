<?php

declare(strict_types=1);

namespace NMSTracker\Outposts;

use Application_Exception;
use Application_FilterCriteria_Database_CustomColumn;
use classes\NMSTracker\Outposts\OutpostRecord;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\Area\SolarSystemsScreen;
use NMSTracker\OutpostRoles\OutpostRoleRecord;
use NMSTracker\OutpostsCollection;
use NMSTracker\OutpostServices\OutpostServiceRecord;
use NMSTracker\OutpostServicesCollection;
use NMSTracker\PlanetsCollection;
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

    public function withServiceCounts() : self
    {
        return $this->addSelectColumn($this->getColServicesCount()->getPrimarySelectValue());
    }

    public function getColLabel() : string
    {
        return (string)$this->statement('{table_outposts}.{outpost_label}');
    }

    public function getColServicesCount() : Application_FilterCriteria_Database_CustomColumn
    {
        return $this->getCustomColumn(self::CUSTOM_COL_SERVICE_COUNT);
    }

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

    public function getColPlanetID() : string
    {
        return (string)$this->statement('{planets}.{planet_primary}');
    }

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            $this->getColPlanetID(),
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );

        $this->addWhereColumnIN(
            OutpostsCollection::COL_ROLE_ID,
            $this->getCriteriaValues(self::FILTER_ROLES)
        );

        $this->addJoinStatement("
            JOIN
                {table_planets} AS {planets}
            ON
                {planets}.{planet_primary} = {table_outposts}.{planet_primary}
        ");

        $this->addWhereColumnIN(
            (string)$this->statement("{planets}.{system_primary}"),
            $this->getCriteriaValues(self::FILTER_SOLAR_SYSTEMS)
        );

        $serviceIDs = $this->getCriteriaValues(self::FILTER_SERVICES);

        // To ensure we find all entries that have all the selected
        // service IDs, we must look up the services for each service
        // individually, and connect the where statements.
        if(!empty($serviceIDs))
        {
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
            ->field('{system_primary}', SolarSystemsCollection::PRIMARY_NAME);
    }
}
