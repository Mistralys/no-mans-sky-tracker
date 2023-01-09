<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

class ResourceContainer
{
    /**
     * @var ResourceRecord[]
     */
    private array $services = array();

    private function __construct()
    {
    }

    public function renderBulletList() : string
    {
        return (new ResourceBulletRenderer($this))->render();
    }

    /**
     * @param ResourceRecord[]|ResourceRecord|ResourceFilterCriteria|NULL $value
     * @return ResourceContainer
     */
    public static function create($value=null) : ResourceContainer
    {
        $container = new self();

        if($value instanceof ResourceFilterCriteria)
        {
            $container->addFilters($value);
        }
        else if($value instanceof ResourceRecord)
        {
            $container->addResource($value);
        }
        else if (is_array($value))
        {
            $container->addResources($value);
        }

        return $container;
    }

    /**
     * @return ResourceRecord[]
     */
    public function getAll() : array
    {
        return array_values($this->services);
    }

    public function addResource(ResourceRecord $service) : self
    {
        $this->services[$service->getID()] = $service;
        return $this;
    }

    public function addFilters(ResourceFilterCriteria $filterCriteria) : self
    {
        return $this->addResources($filterCriteria->getItemsObjects());
    }

    /**
     * @param ResourceRecord[] $services
     * @return $this
     */
    public function addResources(array $services) : self
    {
        foreach($services as $service)
        {
            $this->addResource($service);
        }

        return $this;
    }
}
