<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\PlanetTypes\PlanetTypeRecord;

interface ViewPlanetTypeInterface
{
    public function getPlanetType() : PlanetTypeRecord;
}
