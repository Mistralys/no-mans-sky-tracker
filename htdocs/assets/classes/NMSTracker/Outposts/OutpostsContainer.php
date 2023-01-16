<?php

declare(strict_types=1);

namespace NMSTracker\Outposts;

use NMSTracker\Outposts\Container\OutpostsBulletRenderer;
use NMSTracker\Outposts\OutpostRecord;

class OutpostsContainer
{
    /**
     * @var array<int,OutpostRecord>
     */
    private array $outposts = array();

    private function __construct()
    {
    }

    public function createBulletRenderer() : OutpostsBulletRenderer
    {
        return new OutpostsBulletRenderer($this);
    }

    /**
     * @param OutpostsContainer|OutpostRecord[]|OutpostFilterCriteria|NULL $value
     * @return OutpostsContainer
     */
    public static function create($value=null) : OutpostsContainer
    {
        $container = new self();

        if($value instanceof self)
        {
            $container->addOutposts($value->getAll());
        }
        else if($value instanceof OutpostFilterCriteria)
        {
            $container->addFilterCriteria($value);
        }
        else if(is_array($value))
        {
            $container->addOutposts($value);
        }

        return $container;
    }

    /**
     * @return OutpostRecord[]
     */
    public function getAll() : array
    {
        uasort($this->outposts, static function (OutpostRecord $a, OutpostRecord $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return array_values($this->outposts);
    }

    public function addFilterCriteria(OutpostFilterCriteria $filters) : self
    {
        return $this->addOutposts($filters->getItemsObjects());
    }

    public function addContainer(OutpostsContainer $container) : self
    {
        return $this->addOutposts($container->getAll());
    }

    /**
     * @param OutpostRecord[] $outposts
     * @return $this
     */
    public function addOutposts(array $outposts) : self
    {
        foreach($outposts as $outpost)
        {
            $this->addOutpost($outpost);
        }

        return $this;
    }

    public function addOutpost(OutpostRecord $record) : self
    {
         $this->outposts[$record->getID()] = $record;
         return $this;
    }

    public function hasOutpost(OutpostRecord $record) : bool
    {
        return isset($this->outposts[$record->getID()]);
    }
}
