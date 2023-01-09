<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use DBHelper_BaseRecord;
use NMSTracker\ResourcesCollection;

class ResourceRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(ResourcesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
