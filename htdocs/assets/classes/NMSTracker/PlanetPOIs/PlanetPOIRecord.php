<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetPOIsCollection;

class PlanetPOIRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(PlanetPOIsCollection::COL_LABEL);
    }

    public function getPlanetID() : int
    {
        return $this->getRecordIntKey(PlanetPOIsCollection::COL_PLANET_ID);
    }

    public function getPlanet() : PlanetRecord
    {
        return ClassFactory::createPlanets()->getByID($this->getPlanetID());
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }
}
