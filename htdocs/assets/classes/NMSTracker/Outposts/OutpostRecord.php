<?php

declare(strict_types=1);

namespace classes\NMSTracker\Outposts;

use Application_Admin_ScreenInterface;
use Application_Exception_DisposableDisposed;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper;
use DBHelper_BaseRecord;
use DBHelper_Exception;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\OutpostSettingsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\OutpostStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\OutpostRoles\OutpostRoleRecord;
use NMSTracker\OutpostsCollection;
use NMSTracker\OutpostServices\OutpostServiceFilterCriteria;
use NMSTracker\OutpostServices\OutpostServiceRecord;
use NMSTracker\OutpostServicesCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker_User;
use UI;
use UI_Icon;

class OutpostRecord extends DBHelper_BaseRecord
{
    public function getPlanet() : PlanetRecord
    {
        return ClassFactory::createPlanets()->getByID($this->getPlanetID());
    }

    public function getPlanetID() : int
    {
        return $this->getRecordIntKey(OutpostsCollection::COL_PLANET_ID);
    }

    public function getIcon() : UI_Icon
    {
        return NMSTracker::icon()->outpost();
    }

    public function getLabel() : string
    {
        return $this->getRecordStringKey(OutpostsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminStatusURL(),
            NMSTracker_User::RIGHT_VIEW_OUTPOSTS
        );
    }

    public function getSolarSystem() : SolarSystemRecord
    {
        return $this->getPlanet()->getSolarSystem();
    }

    public function getRoleID() : int
    {
        return $this->getRecordIntKey(OutpostsCollection::COL_ROLE_ID);
    }

    public function getRole() : OutpostRoleRecord
    {
        return ClassFactory::createOutpostRoles()->getByID($this->getRoleID());
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(OutpostsCollection::COL_COMMENTS);
    }

    public function countServices() : int
    {
        return $this->getServiceFilters()->countItems();
    }

    public function getServiceFilters() : OutpostServiceFilterCriteria
    {
        return ClassFactory::createOutpostServices()
            ->getFilterCriteria()
            ->includeIDs($this->getServiceIDs(), false);
    }

    /**
     * @return int[]
     */
    public function getServiceIDs() : array
    {
        return DBHelper::createFetchMany(OutpostsCollection::TABLE_SERVICES)
            ->selectColumn(OutpostServicesCollection::PRIMARY_NAME)
            ->whereValue(OutpostsCollection::PRIMARY_NAME, $this->getID())
            ->fetchColumnInt(OutpostServicesCollection::PRIMARY_NAME);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getAdminURL(array $params=array()) : string
    {
        $params[OutpostsCollection::PRIMARY_NAME] = $this->getID();

        return $this->getPlanet()->getAdminURL($params);
    }

    public function getAdminStatusURL(array $params=array()) : string
    {
         $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = OutpostStatusScreen::URL_NAME;

         return $this->getAdminURL($params);
    }

    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = OutpostSettingsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<int,int|string> $serviceIDs
     * @return void
     * @throws Application_Exception_DisposableDisposed
     * @throws DBHelper_Exception
     */
    public function updateServicesFromForm(array $serviceIDs) : void
    {
        DBHelper::requireTransaction('Update outpost services');

        $existing = $this->getServiceIDs();
        $collection = ClassFactory::createOutpostServices();

        // Add new resources, if they do not exist already
        foreach($serviceIDs as $newResourceID)
        {
            $newResourceID = (int)$newResourceID;

            if(in_array($newResourceID, $existing, true))
            {
                continue;
            }

            $this->addService($collection->getByID($newResourceID));
        }

        // Remove existing resources that do not exist in the new list
        foreach($existing as $oldResourceID)
        {
            if(!in_array($oldResourceID, $serviceIDs, false))
            {
                $this->removeService($collection->getByID($oldResourceID));
            }
        }
    }

    public function addService(OutpostServiceRecord $service) : self
    {
        if($this->hasService($service)) {
            return $this;
        }

        $this->log(
            'Services | Adding service [#%s %s].',
            $service->getID(),
            $service->getLabel()
        );

        DBHelper::insertDynamic(
            OutpostsCollection::TABLE_SERVICES,
            array(
                OutpostsCollection::PRIMARY_NAME => $this->getID(),
                OutpostServicesCollection::PRIMARY_NAME => $service->getID()
            )
        );

        return $this;
    }

    public function removeService(OutpostServiceRecord $service) : self
    {
        if(!$this->hasService($service)) {
            return $this;
        }

        $this->log(
            'Services | Removing service [#%s %s].',
            $service->getID(),
            $service->getLabel()
        );

        DBHelper::deleteRecords(
            OutpostsCollection::TABLE_SERVICES,
            array(
                OutpostsCollection::PRIMARY_NAME => $this->getID(),
                OutpostServicesCollection::PRIMARY_NAME => $service->getID()
            )
        );

        return $this;
    }

    public function hasService(OutpostServiceRecord $service) : bool
    {
        return in_array($service->getID(), $this->getServiceIDs(), true);
    }
}
