<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\Resources\ResourceFilterSettings;
use NMSTracker\Resources\ResourceRecord;

/**
 * @method ResourceRecord getByID(int $record_id)
 * @method ResourceRecord[] getAll()
 * @method ResourceFilterCriteria getFilterCriteria()
 * @method ResourceFilterSettings getFilterSettings()
 */
class ResourcesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'resources';
    public const PRIMARY_NAME = 'resource_id';

    public const COL_LABEL = 'label';

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
}
