<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\PlanetPOIs\PlanetPOIRecord;

interface ViewPOIScreenInterface extends ViewPlanetScreenInterface
{
    public function getPOI() : PlanetPOIRecord;
}
