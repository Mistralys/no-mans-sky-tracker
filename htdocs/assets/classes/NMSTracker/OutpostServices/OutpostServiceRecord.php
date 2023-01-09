<?php

declare(strict_types=1);

namespace NMSTracker\OutpostServices;

use DBHelper_BaseRecord;
use NMSTracker\OutpostServicesCollection;

class OutpostServiceRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(OutpostServicesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
