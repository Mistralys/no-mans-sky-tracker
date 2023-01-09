<?php

declare(strict_types=1);

namespace NMSTracker;

use NMSTracker\OutpostServices\OutpostServiceFilterCriteria;
use NMSTracker\OutpostServices\OutpostServiceFilterSettings;
use NMSTracker\OutpostServices\OutpostServiceRecord;

/**
 * @method OutpostServiceRecord getByID(int $record_id)
 * @method OutpostServiceRecord|NULL getByRequest()
 * @method OutpostServiceRecord[] getAll()
 * @method OutpostServiceFilterSettings getFilterSettings()
 * @method OutpostServiceFilterCriteria getFilterCriteria()
 */
class OutpostServicesCollection extends \DBHelper_BaseCollection
{
    public const TABLE_NAME = 'outpost_services';
    public const PRIMARY_NAME = 'outpost_service_id';

    public const COL_LABEL = 'label';

    public function getRecordClassName() : string
    {
        return OutpostServiceRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return OutpostServiceFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return OutpostServiceFilterSettings::class;
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
        return 'outpost_service';
    }

    public function getCollectionLabel() : string
    {
        return t('Outpost services');
    }

    public function getRecordLabel() : string
    {
        return t('Outpost service');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}