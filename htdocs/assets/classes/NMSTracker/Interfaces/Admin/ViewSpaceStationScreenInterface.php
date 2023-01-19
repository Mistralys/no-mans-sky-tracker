<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use Application_Admin_ScreenInterface;
use NMSTracker\SpaceStations\SpaceStationRecord;

interface ViewSpaceStationScreenInterface extends Application_Admin_ScreenInterface
{
    public function getSpaceStation() : SpaceStationRecord;
}
