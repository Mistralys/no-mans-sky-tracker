<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use NMSTracker\Resources\Types\Collectible;
use NMSTracker\Resources\Types\Harvestable;
use NMSTracker\Resources\Types\Mineral;
use NMSTracker\Resources\Types\Specialty;
use NMSTracker\Resources\Types\Tradeable;

class ResourceTypesCollection
{
    public const ERROR_UNKNOWN_RESOURCE_ID = 124201;

    public const TYPE_MINERAL = 'mineral';
    public const TYPE_HARVESTABLE = 'harvestable';
    public const TYPE_COLLECTIBLE = 'collectible';
    public const TYPE_SPECIALTY = 'specialty';
    public const TYPE_TRADEABLE = 'tradeable';

    private static ?ResourceTypesCollection $instance = null;

    /**
     * @var BaseResourceType[]
     */
    private array $types;

    public static function getInstance() : ResourceTypesCollection
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->types = array(
            new Mineral(),
            new Harvestable(),
            new Specialty(),
            new Tradeable(),
            new Collectible()
        );

        usort($this->types, static function (BaseResourceType $a, BaseResourceType $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    public function idExists(string $id) : bool
    {
        return in_array($id, $this->getIDs(), true);
    }

    public function getByID(string $id) : BaseResourceType
    {
        foreach($this->types as $type)
        {
            if($type->getID() === $id) {
                return $type;
            }
        }

        throw new ResourceException(
            'No such resource type.',
            sprintf(
                'The resource type ID [%s] does not exist. Valid IDs are: [%s].',
                $id,
                implode(', ', $this->getIDs())
            ),
            self::ERROR_UNKNOWN_RESOURCE_ID
        );
    }

    public function getIDs() : array
    {
        $result = array();

        foreach ($this->types as $type)
        {
            $result[] = $type->getID();
        }

        return $result;
    }


    public function getAll() : array
    {
        return $this->types;
    }
}
