<?php

declare(strict_types=1);

namespace NMSTracker\SentinelLevels;

use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\SentinelLevelsCollection;
use UI;
use UI_Label;

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
        return (string)sb()
            ->add($this->getAggressionLevel()->getBadge())
            ->add($this->getLabel());
    }

    public function getAggressionLevel() : SentinelAggressionLevel
    {
        return SentinelAggressionLevels::getInstance()->getByID($this->getAggressionLevelID());
    }

    public function getAggressionLevelID() : string
    {
        return $this->getRecordStringKey(SentinelLevelsCollection::COL_AGGRESSION_LEVEL);
    }
}
