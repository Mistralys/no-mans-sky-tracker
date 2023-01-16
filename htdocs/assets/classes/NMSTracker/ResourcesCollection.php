<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Driver;
use AppUtils\Interface_Stringable;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use NMSTracker\Area\ResourcesScreen;
use NMSTracker\Area\ResourcesScreen\ResourcesListScreen;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\Resources\ResourceFilterSettings;
use NMSTracker\Resources\ResourceRecord;

/**
 * @method ResourceRecord getByID(int $record_id)
 * @method ResourceRecord[] getAll()
 * @method ResourceRecord|NULL getByRequest()
 * @method ResourceFilterCriteria getFilterCriteria()
 * @method ResourceFilterSettings getFilterSettings()
 */
class ResourcesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'resources';
    public const PRIMARY_NAME = 'resource_id';

    public const COL_LABEL = 'label';
    public const COL_TYPE = 'type';

    public function getRecordClassName() : string
    {
        return ResourceRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return ResourceFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return ResourceFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return self::COL_LABEL;
    }

    public function getRecordSearchableColumns() : array
    {
        return array(
            self::COL_LABEL => t('Label')
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
        return 'resource';
    }

    public function getCollectionLabel() : string
    {
        return t('Resources');
    }

    public function getRecordLabel() : string
    {
        return t('Resource');
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
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = ResourcesListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = ResourcesScreen::URL_NAME;

        return Application_Driver::getInstance()
            ->getRequest()
            ->buildURL($params);
    }
}
