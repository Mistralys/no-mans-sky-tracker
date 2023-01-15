<?php

declare(strict_types=1);

namespace NMSTracker\PlanetTypes;

use Application_Admin_ScreenInterface;
use AppUtils\Interface_Stringable;
use DBHelper_BaseRecord;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen\PlanetTypePlanetsScreen;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen\PlanetTypeSettingsScreen;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen\PlanetTypeStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\PlanetTypesCollection;
use NMSTracker_User;

/**
 * @property PlanetTypesCollection $collection
 */
class PlanetTypeRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(PlanetTypesCollection::COL_LABEL);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminPlanetsURL(),
            NMSTracker_User::RIGHT_VIEW_PLANET_TYPES
        );
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminStatusURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = PlanetTypeStatusScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminPlanetsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = PlanetTypePlanetsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = PlanetTypeSettingsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = PlanetTypeScreen::URL_NAME;
        $params[PlanetTypesCollection::PRIMARY_NAME] = $this->getID();

        return $this->collection->getAdminURL($params);
    }

    public function countPlanets() : int
    {
        return $this->getPlanetFilters()->countItems();
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return ClassFactory::createPlanets()
            ->getFilterCriteria()
            ->selectPlanetType($this);
    }

    public function isEditable() : bool
    {
        return true;
    }
}
