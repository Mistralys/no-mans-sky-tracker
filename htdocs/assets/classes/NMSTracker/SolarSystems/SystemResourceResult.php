<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use Application_Exception_DisposableDisposed;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_Exception;
use NMSTracker\ClassFactory;
use NMSTracker\Resources\ResourceRecord;

class SystemResourceResult
{
    private int $resourceID;

    /**
     * @var int[]
     */
    private array $planetIDs;
    private SolarSystemRecord $solarSystem;

    /**
     * @param int $resourceID
     * @param int[] $planetIDs
     */
    public function __construct(SolarSystemRecord $solarSystem, int $resourceID, array $planetIDs)
    {
        $this->solarSystem = $solarSystem;
        $this->resourceID = $resourceID;
        $this->planetIDs = $planetIDs;
    }

    public function getSolarSystem() : SolarSystemRecord
    {
        return $this->solarSystem;
    }

    /**
     * @return int[]
     */
    public function getPlanetIDs() : array
    {
        return $this->planetIDs;
    }

    /**
     * @return PlanetRecord[]
     * @throws Application_Exception_DisposableDisposed
     * @throws DBHelper_Exception
     */
    public function getPlanets() : array
    {
        $collection = ClassFactory::createPlanets();
        $result = array();

        foreach($this->planetIDs as $planetID)
        {
            $result[] = $collection->getByID($planetID);
        }

        return $result;
    }

    public function getResourceID() : int
    {
        return $this->resourceID;
    }

    public function getResource() : ResourceRecord
    {
        return ClassFactory::createResources()->getByID($this->getResourceID());
    }
}