<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Formable;
use Application_Request;
use AppUtils\ClassHelper\ClassNotExistsException;
use AppUtils\ClassHelper\ClassNotImplementsException;
use AppUtils\Interface_Stringable;
use DBHelper_BaseCollection;
use NMSTracker\Area\SolarSystemsScreen;
use NMSTracker\Area\SolarSystemsScreen\CreateSystemScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemAddPlanetScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemsListScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemStatusScreen;
use NMSTracker\SolarSystems\SolarSystemFilterCriteria;
use NMSTracker\SolarSystems\SolarSystemFilterSettings;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystems\SystemSettingsManager;

/**
 * @method SolarSystemRecord getByID(int $record_id)
 * @method SolarSystemRecord[] getAll()
 * @method SolarSystemFilterCriteria getFilterCriteria()
 * @method SolarSystemFilterSettings getFilterSettings()
 */
class SolarSystemsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'solar_systems';
    public const PRIMARY_NAME = 'solar_system_id';

    public const COL_LABEL = 'label';
    public const COL_RACE_ID = 'race_id';
    public const COL_STAR_TYPE_ID = StarTypesCollection::PRIMARY_NAME;
    public const COL_CLUSTER_ID = ClustersCollection::PRIMARY_NAME;
    public const COL_COMMENTS = 'comments';
    public const COL_AMOUNT_PLANETS = 'amount_planets';
    public const COL_IS_OWN_DISCOVERY = 'is_own_discovery';
    public const COL_DATE_ADDED = 'date_added';

    public function getRecordClassName() : string
    {
        return SolarSystemRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return SolarSystemFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return SolarSystemFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return self::COL_LABEL;
    }

    public function getRecordSearchableColumns() : array
    {
        return array(
            self::COL_LABEL => t('Name')
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
        return 'solar_system';
    }

    public function getCollectionLabel() : string
    {
        return t('Solar systems');
    }

    public function getRecordLabel() : string
    {
        return t('Solar system');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    public function createSettingsManager(Application_Formable $formable, ?SolarSystemRecord $record=null) : SystemSettingsManager
    {
        return new SystemSettingsManager($formable, $this, $record);
    }

    /**
     * @param array<string,number|string|Interface_Stringable|NULL> $params
     * @return string
     * @throws ClassNotExistsException
     * @throws ClassNotImplementsException
     */
    public function getAdminListURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = SystemsListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,number|string|Interface_Stringable|NULL> $params
     * @return string
     * @throws ClassNotExistsException
     * @throws ClassNotImplementsException
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = SolarSystemsScreen::URL_NAME;

        return Application_Request::getInstance()->buildURL($params);
    }

    /**
     * @param array<string,number|string|Interface_Stringable|NULL> $params
     * @return string
     * @throws ClassNotExistsException
     * @throws ClassNotImplementsException
     */
    public function getAdminCreateURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = CreateSystemScreen::URL_NAME;

        return $this->getAdminURL($params);
    }
}
