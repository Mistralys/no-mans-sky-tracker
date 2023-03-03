<?php

declare(strict_types=1);

use AppUtils\ConvertHelper;
use NMSTracker\Ajax\BaseAjaxMethod;
use NMSTracker\PlanetsCollection;

class NMSTracker_AjaxMethods_SetPlanetFallMade extends BaseAjaxMethod
{
    public const REQUEST_STATE = 'state';

    public function processJSON() : void
    {
        $planet = $this->getPlanet();
        $state = $this->request->getBool(self::REQUEST_STATE);

        $this->startTransaction();

        $planet->setPlanetFallMade($state);
        $planet->save();

        $this->endTransaction();

        $this->sendResponse(array(
            PlanetsCollection::PRIMARY_NAME => $planet->getID(),
            self::REQUEST_STATE => ConvertHelper::boolStrict2string($planet->isPlanetFallMade())
        ));
    }
}
