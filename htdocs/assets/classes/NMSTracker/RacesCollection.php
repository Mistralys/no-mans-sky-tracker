<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\Races\RaceFilterCriteria;
use NMSTracker\Races\RaceFilterSettings;
use NMSTracker\Races\RaceRecord;

/**
 * @method RaceRecord getByID(int $record_id)
 * @method RaceRecord[] getAll()
 * @method RaceFilterSettings getFilterSettings()
 * @method RaceFilterCriteria getFilterCriteria()
 */
class RacesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'races';
    public const PRIMARY_NAME = 'race_id';

    public const COL_LABEL = 'label';

    public function getRecordClassName() : string
    {
        return RaceRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return RaceFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return RaceFilterSettings::class;
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
        return 'race';
    }

    public function getCollectionLabel() : string
    {
        return t('Alien races');
    }

    public function getRecordLabel() : string
    {
        return t('Alien race');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
