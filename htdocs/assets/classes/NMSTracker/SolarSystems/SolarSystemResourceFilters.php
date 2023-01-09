<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use DBHelper;
use NMSTracker\PlanetsCollection;
use NMSTracker\ResourcesCollection;

class SolarSystemResourceFilters
{
    /**
     * @var SystemResourceResult[]
     */
    private array $results = array();
    private SolarSystemRecord $solarSystem;

    public function __construct(SolarSystemRecord $solarSystem)
    {
        $this->solarSystem = $solarSystem;

        $this->findResources();
    }

    private function findResources() : void
    {
        $data = $this->fetchData();
        $byResource = array();

        foreach ($data as $entry)
        {
            $resourceID = (int)$entry[ResourcesCollection::PRIMARY_NAME];
            $planetID = (int)$entry[PlanetsCollection::PRIMARY_NAME];

            if(!isset($byResource[$resourceID]))
            {
                $byResource[$resourceID] = array();
            }

            if(!in_array($planetID, $byResource[$resourceID], true))
            {
                $byResource[$resourceID][] = $planetID;
            }
        }

        foreach($byResource as $resourceID => $planetIDs)
        {
            $this->results[] = new SystemResourceResult(
                $this->solarSystem,
                $resourceID,
                $planetIDs
            );
        }
    }

    private function fetchData() : array
    {
        return DBHelper::createFetchMany(PlanetsCollection::TABLE_RESOURCES)
            ->selectColumns(ResourcesCollection::PRIMARY_NAME, PlanetsCollection::PRIMARY_NAME)
            ->whereValueIN(PlanetsCollection::PRIMARY_NAME, $this->solarSystem->getPlanetFilters()->getIDs())
            ->fetch();
    }

    /**
     * @return SystemResourceResult[]
     */
    public function getResults() : array
    {
        return $this->results;
    }

    /**
     * @return int[]
     */
    public function getResourceIDs() : array
    {
        $ids = array();

        foreach($this->results as $result)
        {
            $ids[] = $result->getResourceID();
        }

        return $ids;
    }
}
