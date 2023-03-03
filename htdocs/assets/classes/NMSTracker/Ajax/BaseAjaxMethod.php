<?php

declare(strict_types=1);

namespace NMSTracker\Ajax;

use Application_AjaxMethod;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetRecord;

abstract class BaseAjaxMethod extends Application_AjaxMethod
{
    public const ERROR_NO_PLANET_SPECIFIED = 131201;

    public function getPlanet() : PlanetRecord
    {
        $planet = ClassFactory::createPlanets()->getByRequest();

        if($planet !== null)
        {
            return $planet;
        }

        $this->sendError(
            'No planet specified.',
            null,
            self::ERROR_NO_PLANET_SPECIFIED
        );
    }
}
