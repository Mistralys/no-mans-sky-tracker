<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\PlanetPOIs\PlanetPOIFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIFilterSettings;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;

/**
 * @method PlanetPOIRecord getByID(int $record_id)
 * @method PlanetPOIRecord|NULL getByRequest()
 * @method PlanetPOIRecord[] getAll()
 * @method PlanetPOIFilterSettings getFilterSettings()
 * @method PlanetPOIFilterCriteria getFilterCriteria()
 */
class PlanetPOIsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'planet_pois';
    public const PRIMARY_NAME = 'planet_poi_id';

    public const COL_LABEL = 'label';
    public const COL_PLANET_ID = PlanetsCollection::PRIMARY_NAME;
    public const COL_COORDINATE_A = 'coord_a';
    public const COL_COORDINATE_B = 'coord_b';

    public function getRecordClassName() : string
    {
        return PlanetPOIRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return PlanetPOIFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return PlanetPOIFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return PlanetPOIFilterCriteria::DEFAULT_SORT_KEY;
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
        return 'planet_poi';
    }

    public function getCollectionLabel() : string
    {
        return t('Planetary points of interest');
    }

    public function getRecordLabel() : string
    {
        return t('Planetary point of interest');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
