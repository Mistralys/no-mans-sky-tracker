<?php

declare(strict_types=1);

namespace NMSTracker\OutpostServices;

class OutpostServiceContainer
{
    /**
     * @var OutpostServiceRecord[]
     */
    private array $services = array();

    private function __construct()
    {
    }

    public function renderBulletList() : string
    {
        return (new OutpostServiceBulletRenderer($this))->render();
    }

    /**
     * @param OutpostServiceRecord[]|OutpostServiceRecord|OutpostServiceFilterCriteria|NULL $value
     * @return OutpostServiceContainer
     */
    public static function create($value=null) : OutpostServiceContainer
    {
        $container = new self();

        if($value instanceof OutpostServiceFilterCriteria)
        {
            $container->addFilters($value);
        }
        else if($value instanceof OutpostServiceRecord)
        {
            $container->addService($value);
        }
        else if (is_array($value))
        {
            $container->addServices($value);
        }

        return $container;
    }

    /**
     * @return OutpostServiceRecord[]
     */
    public function getAll() : array
    {
        return array_values($this->services);
    }

    public function addService(OutpostServiceRecord $service) : self
    {
        $this->services[$service->getID()] = $service;
        return $this;
    }

    public function addFilters(OutpostServiceFilterCriteria $filterCriteria) : self
    {
        return $this->addServices($filterCriteria->getItemsObjects());
    }

    /**
     * @param OutpostServiceRecord[] $services
     * @return $this
     */
    public function addServices(array $services) : self
    {
        foreach($services as $service)
        {
            $this->addService($service);
        }

        return $this;
    }
}
