<?php

declare(strict_types=1);

namespace NMSTracker\PlanetTypes;

use DBHelper_BaseRecord;
use NMSTracker\PlanetTypesCollection;

class PlanetTypeRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(PlanetTypesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
