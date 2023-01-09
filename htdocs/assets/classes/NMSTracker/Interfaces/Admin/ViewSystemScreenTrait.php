<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\SolarSystems\SolarSystemRecord;

/**
 * @see ViewSystemScreenInterface
 */
trait ViewSystemScreenTrait
{
    public function getSolarSystem() : SolarSystemRecord
    {
        return $this->mode->getRecord();
    }
}
