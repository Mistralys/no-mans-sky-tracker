<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Formable;
use Application_Request;
use AppUtils\Interface_Stringable;
use DBHelper_BaseCollection;
use NMSTracker\Area\SpaceStationsScreen;
use NMSTracker\Area\SpaceStationsScreen\CreateStationScreen;
use NMSTracker\Area\SpaceStationsScreen\StationsListScreen;
use NMSTracker\SpaceStations\SpaceStationFilterCriteria;
use NMSTracker\SpaceStations\SpaceStationFilterSettings;
use NMSTracker\SpaceStations\SpaceStationRecord;
use NMSTracker\SpaceStations\SpaceStationSettingsManager;
use NMSTracker\SpaceStations\StationOfferTypesCollection;

/**
 * @method SpaceStationRecord getByID(int $record_id)
 * @method SpaceStationRecord|NULL getByRequest()
 * @method SpaceStationRecord[] getAll()
 * @method SpaceStationFilterSettings getFilterSettings()
 * @method SpaceStationFilterCriteria getFilterCriteria()
 */
class SpaceStationsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'space_stations';
    public const TABLE_RESOURCES = 'space_stations_resources';
    public const PRIMARY_NAME = 'space_station_id';

    public const COL_LABEL = 'label';
    public const COL_SOLAR_SYSTEM_ID = SolarSystemsCollection::PRIMARY_NAME;
    public const COL_COMMENTS = 'comments';
    public const COL_RESOURCE_OFFER_TYPE = 'offer_type';

    public function getRecordClassName() : string
    {
        return SpaceStationRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return SpaceStationFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return SpaceStationFilterSettings::class;
    }

    public function createSettingsManager(Application_Formable $formable, ?SpaceStationRecord $record) : SpaceStationSettingsManager
    {
        return new SpaceStationSettingsManager($formable, $this, $record);
    }

    private ?StationOfferTypesCollection $offerTypes = null;

    public function getOfferTypes() : StationOfferTypesCollection
    {
        if(!isset($this->offerTypes)) {
            $this->offerTypes = new StationOfferTypesCollection();
        }

        return $this->offerTypes;
    }

    public function getRecordDefaultSortKey() : string
    {
        return sprintf(
            '`%s`.`%s`',
            self::TABLE_NAME,
            self::COL_LABEL
        );
    }

    public function getRecordSearchableColumns() : array
    {
        return array(
            self::COL_LABEL => t('Name'),
            self::COL_COMMENTS => t('Comments')
        );
    }

    public function getRecordTableName() : string
    {
        return self::TABLE_NAME;
    }

    public function getRecordPrimaryName() : string
    {
        return self::PRIMARY_NAME;
    }

    public function getRecordTypeName() : string
    {
        return 'space_station';
    }

    public function getCollectionLabel() : string
    {
        return t('Space stations');
    }

    public function getRecordLabel() : string
    {
        return t('Space station');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminListURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = StationsListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminCreateURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = CreateStationScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = SpaceStationsScreen::URL_NAME;

        return Application_Request::getInstance()->buildURL($params);
    }
}
