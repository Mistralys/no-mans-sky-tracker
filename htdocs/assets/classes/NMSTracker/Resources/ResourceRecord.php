<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use Application_Admin_ScreenInterface;
use AppUtils\Interface_Stringable;
use DBHelper_BaseRecord;
use NMSTracker\Area\ResourcesScreen\ResourceScreen;
use NMSTracker\Area\ResourcesScreen\ResourceScreen\ResourceOutpostsScreen;
use NMSTracker\Area\ResourcesScreen\ResourceScreen\ResourcePlanetsScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemFilterCriteria;

/**
 * @method ResourcesCollection getCollection()
 */
class ResourceRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(ResourcesCollection::COL_LABEL);
    }

    public function getTypeID() : string
    {
        return $this->getRecordStringKey(ResourcesCollection::COL_TYPE);
    }

    public function getType() : BaseResourceType
    {
        return ClassFactory::createResourceTypes()->getByID($this->getTypeID());
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->link(
            $this->getLabel(),
            $this->getAdminPlanetsURL()
        );
    }

    public function getSolarSystemFilters() : SolarSystemFilterCriteria
    {
        return ClassFactory::createSolarSystems()
            ->getFilterCriteria()
            ->selectResource($this);
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return ClassFactory::createPlanets()
            ->getFilterCriteria()
            ->selectResource($this);
    }

    public function getOutpostFilters() : OutpostFilterCriteria
    {
        return ClassFactory::createOutposts()
            ->getFilterCriteria()
            ->selectResource($this);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[ResourcesCollection::PRIMARY_NAME] = $this->getID();
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = ResourceScreen::URL_NAME;

        return $this->getCollection()->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminPlanetsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = ResourcePlanetsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminOutpostsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = ResourceOutpostsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }
}
