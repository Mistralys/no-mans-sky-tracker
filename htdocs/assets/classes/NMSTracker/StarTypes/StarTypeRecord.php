<?php

declare(strict_types=1);

namespace NMSTracker\StarTypes;

use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\CustomIcon;
use NMSTracker\StarTypesCollection;
use UI_Icon;

class StarTypeRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(StarTypesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function isYellow() : bool
    {
        return $this->getID() === StarTypesCollection::ID_YELLOW;
    }

    public function isBlue() : bool
    {
        return $this->getID() === StarTypesCollection::ID_BLUE;
    }

    public function isRed() : bool
    {
        return $this->getID() === StarTypesCollection::ID_RED;
    }

    public function isGreen() : bool
    {
        return $this->getID() === StarTypesCollection::ID_GREEN;
    }

    public function getIcon() : UI_Icon
    {
        $icon = NMSTracker::icon()
            ->solarSystem()
            ->setTooltip(t('%1$s star', $this->getLabel()))
            ->cursorHelp();

        if($this->isYellow()) {
            $icon->addClass('star-color-yellow');
        } else if($this->isBlue()) {
            $icon->addClass('star-color-blue');
        } else if($this->isRed()) {
            $icon->addClass('star-color-red');
        } else if($this->isGreen()) {
            $icon->addClass('star-color-green');
        }

        return $icon;
    }

    public function getLabelLinked() : string
    {
        return $this->getLabel();
    }
}
