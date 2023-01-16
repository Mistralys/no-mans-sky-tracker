<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\Outposts\OutpostRecord;

interface ViewOutpostScreenInterface extends ViewPlanetScreenInterface
{
    public function getOutpost() : OutpostRecord;
}
