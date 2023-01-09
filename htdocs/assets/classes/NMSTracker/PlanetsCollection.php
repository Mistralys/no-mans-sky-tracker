<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Formable;
use Application_Interfaces_Formable;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\Planets\PlanetFilterSettings;
use NMSTracker\Planets\PlanetSettingsManager;
use NMSTracker\SolarSystems\SolarSystemRecord;

/**
 * @method PlanetRecord getByID(int $record_id)
 * @method PlanetRecord|NULL getByRequest()
 * @method PlanetRecord[] getAll()
 * @method PlanetFilterSettings getFilterSettings()
 * @method PlanetFilterCriteria getFilterCriteria()
 */
class PlanetsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'planets';
    public const TABLE_RESOURCES = 'planets_resources';
    public const PRIMARY_NAME = 'planet_id';

    public const COL_LABEL = 'label';
    public const COL_SOLAR_SYSTEM_ID = SolarSystemsCollection::PRIMARY_NAME;
    public const COL_PLANET_TYPE_ID = PlanetTypesCollection::PRIMARY_NAME;
    public const COL_IS_MOON = 'is_moon';
    public const COL_SENTINEL_LEVEL_ID = SentinelLevelsCollection::PRIMARY_NAME;
    public const COL_SCAN_COMPLETE = 'scan_complete';
    public const COL_COMMENTS = 'comments';

    public function getRecordClassName() : string
    {
        return PlanetRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return PlanetFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return PlanetFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return self::COL_LABEL;
    }

    public function getRecordSearchableColumns() : array
    {
        return array(
            self::COL_LABEL => t('Label'),
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
        return 'planet';
    }

    public function getCollectionLabel() : string
    {
        return t('Planets');
    }

    public function getRecordLabel() : string
    {
        return t('Planet');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    public function getAdminListURL(array $params=array()) : string
    {
        return '';
    }

    public function createSettingsManagerAdd(Application_Formable $formable, SolarSystemRecord $solarSystem) : PlanetSettingsManager
    {
        return new PlanetSettingsManager(
            $formable,
            $this,
            $solarSystem
        );
    }

    public function createSettingsManagerEdit(Application_Formable $formable, PlanetRecord $record) : PlanetSettingsManager
    {
        return new PlanetSettingsManager(
            $formable,
            $this,
            $record->getSolarSystem(),
            $record
        );
    }
}
