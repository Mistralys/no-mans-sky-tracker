<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\StarTypes\StarTypeFilterCriteria;
use NMSTracker\StarTypes\StarTypeFilterSettings;
use NMSTracker\StarTypes\StarTypeRecord;

/**
 * @method StarTypeRecord[] getAll()
 * @method StarTypeRecord getByID(int $record_id)
 * @method StarTypeRecord|NULL getByRequest()
 * @method StarTypeFilterSettings getFilterSettings()
 * @method StarTypeFilterCriteria getFilterCriteria()
 */
class StarTypesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'star_types';
    public const PRIMARY_NAME = 'star_type_id';

    public const ID_YELLOW = 1;
    public const ID_RED = 2;
    public const ID_GREEN = 3;
    public const ID_BLUE = 4;

    public const COL_LABEL = 'label';

    public function getRecordClassName() : string
    {
        return StarTypeRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return StarTypeFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return StarTypeFilterSettings::class;
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
        return 'star_type';
    }

    public function getCollectionLabel() : string
    {
        return t('Star types');
    }

    public function getRecordLabel() : string
    {
        return t('Star type');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
