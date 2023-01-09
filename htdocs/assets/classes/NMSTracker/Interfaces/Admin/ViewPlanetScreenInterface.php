<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use classes\NMSTracker\Planets\PlanetRecord;

/**
 * @see ViewPlanetScreenTrait
 */
interface ViewPlanetScreenInterface extends ViewSystemScreenInterface
{
    public function getPlanet() : PlanetRecord;

    public function getAbstract() : string;
}
