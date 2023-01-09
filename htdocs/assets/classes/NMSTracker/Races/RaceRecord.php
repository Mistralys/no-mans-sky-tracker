<?php

declare(strict_types=1);

namespace NMSTracker\Races;

use DBHelper_BaseRecord;
use NMSTracker\RacesCollection;

class RaceRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(RacesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
