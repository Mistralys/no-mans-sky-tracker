<?php

declare(strict_types=1);

namespace NMSTracker\OutpostRoles;

use DBHelper_BaseRecord;
use NMSTracker\OutpostRolesCollection;

class OutpostRoleRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(OutpostRolesCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }
}
