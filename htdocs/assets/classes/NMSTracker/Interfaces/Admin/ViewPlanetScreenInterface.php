<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\Planets\PlanetRecord;

/**
 * @see ViewPlanetScreenTrait
 */
interface ViewPlanetScreenInterface extends ViewSystemScreenInterface
{
    public function getPlanet() : PlanetRecord;

    public function getAbstract() : string;
}
