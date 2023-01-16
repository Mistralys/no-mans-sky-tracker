<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use NMSTracker\Planets\Container\PlanetsBulletRenderer;

class PlanetsContainer
{
    /**
     * @var array<int,PlanetRecord>
     */
    private array $planets = array();

    private function __construct()
    {
    }

    public static function create($value=null) : PlanetsContainer
    {
        $container = new self();

        if($value instanceof self)
        {
            $container->addContainer($value);
        }
        else if($value instanceof PlanetFilterCriteria)
        {
            $container->addFilters($value);
        }
        else if(is_array($value))
        {
            $container->addPlanets($value);
        }

        return $container;
    }

    public function createBulletRenderer() : PlanetsBulletRenderer
    {
        return new PlanetsBulletRenderer($this);
    }

    public function addContainer(PlanetsContainer $container) : self
    {
        return $this->addPlanets($container->getAll());
    }

    public function addPlanets(array $planets) : self
    {
        foreach($planets as $planet)
        {
            $this->addPlanet($planet);
        }

        return $this;
    }

    public function addPlanet(PlanetRecord $planet) : self
    {
        $this->planets[$planet->getID()] = $planet;
        return $this;
    }

    public function addFilters(PlanetFilterCriteria $filters) : self
    {
        return $this->addPlanets($filters->getItemsObjects());
    }

    /**
     * @return PlanetRecord[]
     */
    public function getAll() : array
    {
        uasort($this->planets, static function(PlanetRecord $a, PlanetRecord $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return array_values($this->planets);
    }
}
