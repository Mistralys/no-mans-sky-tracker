<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\PlanetTypes\PlanetTypeFilterCriteria;
use NMSTracker\PlanetTypes\PlanetTypeFilterSettings;
use NMSTracker\PlanetTypes\PlanetTypeRecord;

/**
 * @method PlanetTypeRecord getByID(int $record_id)
 * @method PlanetTypeRecord[] getAll()
 * @method PlanetTypeFilterSettings getFilterSettings()
 * @method PlanetTypeFilterCriteria getFilterCriteria()
 */
class PlanetTypesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'planet_types';
    public const PRIMARY_NAME = 'planet_type_id';

    public const COL_LABEL = 'label';


    public function getRecordClassName() : string
    {
        return PlanetTypeRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return PlanetTypeFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return PlanetTypeFilterSettings::class;
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
        return 'planet_type';
    }

    public function getCollectionLabel() : string
    {
        return t('Planet types');
    }

    public function getRecordLabel() : string
    {
        return t('Planet type');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
