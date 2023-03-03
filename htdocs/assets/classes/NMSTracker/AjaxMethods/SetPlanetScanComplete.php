<?php

declare(strict_types=1);

use AppUtils\ConvertHelper;
use NMSTracker\Ajax\BaseAjaxMethod;
use NMSTracker\PlanetsCollection;

class NMSTracker_AjaxMethods_SetPlanetScanComplete extends BaseAjaxMethod
{
    public const REQUEST_SCAN_STATE = 'state';

    public function processJSON() : void
    {
        $planet = $this->getPlanet();
        $state = $this->request->getBool(self::REQUEST_SCAN_STATE);

        $this->startTransaction();

        $planet->setScanComplete($state);
        $planet->save();

        $this->endTransaction();

        $this->sendResponse(array(
            PlanetsCollection::PRIMARY_NAME => $planet->getID(),
            self::REQUEST_SCAN_STATE => ConvertHelper::boolStrict2string($planet->isScanComplete())
        ));
    }
}
