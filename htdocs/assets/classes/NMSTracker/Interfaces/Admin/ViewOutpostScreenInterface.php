<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use classes\NMSTracker\Outposts\OutpostRecord;

interface ViewOutpostScreenInterface extends ViewPlanetScreenInterface
{
    public function getOutpost() : OutpostRecord;
}
