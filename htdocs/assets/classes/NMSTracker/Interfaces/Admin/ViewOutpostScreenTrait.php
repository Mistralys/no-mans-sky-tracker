<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use classes\NMSTracker\Outposts\OutpostRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;

trait ViewOutpostScreenTrait
{
    public function getOutpost() : OutpostRecord
    {
        $outpost = ClassFactory::createOutposts()->getByRequest();

        if($outpost !== null) {
            return $outpost;
        }

        $this->redirectWithErrorMessage(
            t('No such outpost found.'),
            $this->getPlanet()->getAdminOutpostsURL()
        );
    }
}
