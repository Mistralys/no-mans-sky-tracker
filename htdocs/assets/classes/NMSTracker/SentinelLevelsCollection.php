<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\SentinelLevels\SentinelLevelFilterCriteria;
use NMSTracker\SentinelLevels\SentinelLevelFilterSettings;
use NMSTracker\SentinelLevels\SentinelLevelRecord;

/**
 * @method SentinelLevelRecord getByID(int $record_id)
 * @method SentinelLevelRecord[] getAll()
 * @method SentinelLevelFilterCriteria getFilterCriteria()
 * @method SentinelLevelFilterSettings getFilterSettings()
 */
class SentinelLevelsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'sentinel_levels';
    public const PRIMARY_NAME = 'sentinel_level_id';

    public const COL_LABEL = 'label';
    public const COL_AGGRESSION_LEVEL = 'aggression_level';
    public const ID_NONE = 1;

    public function getRecordClassName() : string
    {
        return SentinelLevelRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return SentinelLevelFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return SentinelLevelFilterSettings::class;
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
        return 'sentinel_level';
    }

    public function getCollectionLabel() : string
    {
        return t('Sentinel levels');
    }

    public function getRecordLabel() : string
    {
        return t('Sentinel level');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
