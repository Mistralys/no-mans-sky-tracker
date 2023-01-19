<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\ClassFactory;
use NMSTracker\SpaceStations\SpaceStationRecord;

trait ViewSpaceStationScreenTrait
{
    public function getSpaceStation() : SpaceStationRecord
    {
        $collection = ClassFactory::createSpaceStations();
        $station = $collection
            ->getByRequest();

        if($station !== null) {
            return $station;
        }

        $this->redirectTo($collection->getAdminListURL());
    }
}
