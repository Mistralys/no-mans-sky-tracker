<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use Application_Admin_ScreenInterface;
use Application_Request;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemAddPlanetScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemOutpostsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemResourcesScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemSettingsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Clusters\ClusterRecord;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\Races\RaceRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker\StarTypes\StarTypeRecord;
use NMSTracker_User;
use UI;
use UI_Label;
use UI_PropertiesGrid;

class SolarSystemRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(SolarSystemsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminViewURL(),
            NMSTracker_User::RIGHT_VIEW_SOLAR_SYSTEMS
        );
    }

    public function getClusterID() : int
    {
        return $this->getRecordIntKey(SolarSystemsCollection::COL_CLUSTER_ID);
    }

    public function getCluster() : ClusterRecord
    {
        return ClassFactory::createClusters()->getByID($this->getClusterID());
    }

    public function getStarTypeID() : int
    {
        return $this->getRecordIntKey(SolarSystemsCollection::COL_STAR_TYPE_ID);
    }

    public function getStarType() : StarTypeRecord
    {
        return ClassFactory::createStarTypes()->getByID($this->getStarTypeID());
    }

    public function getRaceID() : int
    {
        return $this->getRecordIntKey(SolarSystemsCollection::COL_RACE_ID);
    }

    public function getRace() : RaceRecord
    {
        return ClassFactory::createRaces()->getByID($this->getRaceID());
    }

    public function countPlanets() : int
    {
        return $this->getPlanetFilters()->countItems();
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return ClassFactory::createPlanets()
            ->getFilterCriteria()
            ->selectSolarSystem($this);
    }

    public function getAdminViewURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = SolarSystemsScreen::URL_NAME;
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = SystemScreen::URL_NAME;
        $params[SolarSystemsCollection::PRIMARY_NAME] = $this->getID();

        return Application_Request::getInstance()->buildURL($params);
    }

    public function getAdminStatusURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemStatusScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminPlanetsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemPlanetsScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminOutpostsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemOutpostsScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminResourcesURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemResourcesScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemSettingsScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminCreatePlanetURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = SystemAddPlanetScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getResources() : SolarSystemResourceFilters
    {
        return new SolarSystemResourceFilters($this);
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(SolarSystemsCollection::COL_COMMENTS);
    }

    public function isOwnDiscovery() : bool
    {
        return $this->getRecordBooleanKey(SolarSystemsCollection::COL_IS_OWN_DISCOVERY);
    }

    public function getAmountPlanets() : int
    {
        return $this->getRecordIntKey(SolarSystemsCollection::COL_AMOUNT_PLANETS);
    }

    public function injectProperties(UI_PropertiesGrid $grid) : void
    {
        $grid->add(t('Dominant race'), $this->getRace()->getLabelLinked());
        $grid->add(t('Star type'), $this->getStarType()->getLabelLinked());
    }

    public function getOwnershipBadge() : ?UI_Label
    {
        if($this->isOwnDiscovery()) {
            return UI::label('')
                ->setIcon(NMSTracker::icon()->ownDiscovery())
                ->setTooltip(t('You discovered this.'))
                ->makeSuccess();
        }

        return null;
    }
}
