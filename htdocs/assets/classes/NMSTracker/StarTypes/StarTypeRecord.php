<?php

declare(strict_types=1);

namespace NMSTracker\StarTypes;

use DBHelper_BaseRecord;
use NMSTracker\StarTypesCollection;

class StarTypeRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(StarTypesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
