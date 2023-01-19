<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations;

use Application_Admin_ScreenInterface;
use AppUtils\ConvertHelper_Exception;
use AppUtils\Interface_Stringable;
use DBHelper;
use DBHelper_BaseRecord;
use DBHelper_Exception;
use NMSTracker\Area\SpaceStationsScreen\StationScreen;
use NMSTracker\Area\SpaceStationsScreen\StationScreen\StationSettingsScreen;
use NMSTracker\Area\SpaceStationsScreen\StationScreen\StationStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Resources\ResourceContainer;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SpaceStationsCollection;
use NMSTracker\TrackerException;
use NMSTracker_User;

/**
 * @property SpaceStationsCollection $collection
 */
class SpaceStationRecord extends DBHelper_BaseRecord
{
    /**
     * @param ResourceRecord $resource
     * @return BaseStationOfferType[]
     *
     * @throws ConvertHelper_Exception
     * @throws DBHelper_Exception
     * @throws TrackerException
     */
    public function getResourceOffers(ResourceRecord $resource) : array
    {
        $ids = DBHelper::fetchAllKey(
            SpaceStationsCollection::COL_RESOURCE_OFFER_TYPE,
            statementBuilder(
                "
                SELECT
                    {station_offer_type}
                FROM
                    {table_station_resources}
                WHERE
                    {station_primary}=:primary
                AND
                    {resource_primary}=:resourceID
                ",
                SpaceStationFilterCriteria::getStatementValues()
            ),
            array(
                'primary' => $this->getID(),
                'resourceID' => $resource->getID()
            )
        );

        $types = $this->collection->getOfferTypes();
        $result = array();
        foreach($ids as $id)
        {
            $result[] = $types->getByID($id);
        }

        return $result;
    }

    public function isEditable() : bool
    {
        return true;
    }

    public function getBuyOfferIDs() : array
    {
        return $this->getOfferIDs(StationOfferTypesCollection::OFFER_TYPE_BUY);
    }

    public function getSellOfferIDs() : array
    {
        return $this->getOfferIDs(StationOfferTypesCollection::OFFER_TYPE_SELL);
    }

    public function getSellOffers() : ResourceContainer
    {
        return ResourceContainer::create()->addIDs($this->getSellOfferIDs());
    }

    public function getBuyOffers() : ResourceContainer
    {
        return ResourceContainer::create()->addIDs($this->getBuyOfferIDs());
    }

    public function getOfferIDs(string $type) : array
    {
        return DBHelper::fetchAllKeyInt(
            ResourcesCollection::PRIMARY_NAME,
            statementBuilder(
                "
                SELECT
                    {resource_primary}
                FROM
                    {table_station_resources}
                WHERE
                    {station_primary}=:primary
                AND
                    {station_offer_type}=:offer_type
                ",
                SpaceStationFilterCriteria::getStatementValues()
            ),
            array(
                'primary' => $this->getID(),
                'offer_type' => $type
            )
        );
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getLabel() : string
    {
        return $this->getRecordStringKey(SpaceStationsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminStatusURL(),
            NMSTracker_User::RIGHT_VIEW_SPACE_STATIONS
        );
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(SpaceStationsCollection::COL_COMMENTS);
    }

    public function getSolarSystemID() : int
    {
        return $this->getRecordIntKey(SpaceStationsCollection::COL_SOLAR_SYSTEM_ID);
    }

    public function getSolarSystem() : SolarSystemRecord
    {
        return ClassFactory::createSolarSystems()->getByID($this->getSolarSystemID());
    }

    /**
     * @param array<int,int|string> $resourcesSold
     * @param array<int,int|string> $resourcesBought
     * @return void
     */
    public function updateTradeOffersFromForm(array $resourcesSold, array $resourcesBought) : void
    {
        // Clear all existing resource entries to make it easier
        DBHelper::deleteRecords(
            SpaceStationsCollection::TABLE_RESOURCES,
            array(
                SpaceStationsCollection::PRIMARY_NAME => $this->getID()
            )
        );

        $this->insertResources($resourcesSold, StationOfferTypesCollection::OFFER_TYPE_SELL);
        $this->insertResources($resourcesBought, StationOfferTypesCollection::OFFER_TYPE_BUY);
    }

    /**
     * @param array<int,int|string> $resourceIDs
     * @param string $offerType
     * @return void
     */
    private function insertResources(array $resourceIDs, string $offerType) : void
    {
        foreach($resourceIDs as $resourceID)
        {
            DBHelper::insertDynamic(
                SpaceStationsCollection::TABLE_RESOURCES,
                array(
                    SpaceStationsCollection::PRIMARY_NAME => $this->getID(),
                    ResourcesCollection::PRIMARY_NAME => $resourceID,
                    SpaceStationsCollection::COL_RESOURCE_OFFER_TYPE => $offerType
                )
            );
        }
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[SpaceStationsCollection::PRIMARY_NAME] = $this->getID();
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = StationScreen::URL_NAME;

        return $this->collection->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = StationSettingsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminStatusURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = StationStatusScreen::URL_NAME;

        return $this->getAdminURL($params);
    }
}
