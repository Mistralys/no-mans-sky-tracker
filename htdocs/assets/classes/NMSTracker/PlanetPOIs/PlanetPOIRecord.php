<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use Application_Admin_ScreenInterface;
use AppUtils\Interface_Stringable;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetPOISettingsScreen;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker_User;
use UI_Icon;

class PlanetPOIRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(PlanetPOIsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminSettingsURL(),
            NMSTracker_User::RIGHT_VIEW_POIS
        );
    }

    public function getPlanetID() : int
    {
        return $this->getRecordIntKey(PlanetPOIsCollection::COL_PLANET_ID);
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(PlanetPOIsCollection::COL_COMMENTS);
    }

    public function getSolarSystem() : SolarSystemRecord
    {
        return $this->getPlanet()->getSolarSystem();
    }

    public function getPlanet() : PlanetRecord
    {
        return ClassFactory::createPlanets()->getByID($this->getPlanetID());
    }

    public function getLongitude() : float
    {
        return $this->getRecordFloatKey(PlanetPOIsCollection::COL_COORDINATE_LONGITUDE);
    }

    public function getLatitude() : float
    {
        return $this->getRecordFloatKey(PlanetPOIsCollection::COL_COORDINATE_LATITUDE);
    }

    public function getCoords() : POICoordinates
    {
        return new POICoordinates($this->getLongitude(), $this->getLatitude());
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function isEditable() : bool
    {
        return true;
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetPOISettingsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[PlanetPOIsCollection::PRIMARY_NAME] = $this->getID();

        return $this->getPlanet()->getAdminURL($params);
    }

    public function getIcon() : UI_Icon
    {
        return NMSTracker::icon()->pointsOfInterest();
    }
}
