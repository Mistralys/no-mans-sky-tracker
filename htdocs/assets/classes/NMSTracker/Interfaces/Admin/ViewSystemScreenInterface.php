<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use Application_Admin_ScreenInterface;
use NMSTracker\SolarSystems\SolarSystemRecord;

/**
 * @see ViewSystemScreenTrait
 */
interface ViewSystemScreenInterface extends Application_Admin_ScreenInterface
{
    public function getSolarSystem() : SolarSystemRecord;
}