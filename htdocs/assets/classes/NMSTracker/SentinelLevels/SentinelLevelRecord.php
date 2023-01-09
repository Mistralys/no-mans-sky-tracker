<?php

declare(strict_types=1);

namespace NMSTracker\SentinelLevels;

use DBHelper_BaseRecord;
use NMSTracker\SentinelLevelsCollection;

class SentinelLevelRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(SentinelLevelsCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
