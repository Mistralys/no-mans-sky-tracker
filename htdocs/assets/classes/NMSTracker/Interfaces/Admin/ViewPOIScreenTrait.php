<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\ClassFactory;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;

trait ViewPOIScreenTrait
{
    public function getPOI() : PlanetPOIRecord
    {
        $poi = ClassFactory::createPlanetPOIs()->getByRequest();

        if($poi !== null) {
            return $poi;
        }

        $this->redirectTo($this->getPlanet()->getAdminPOIsURL());
    }
}
