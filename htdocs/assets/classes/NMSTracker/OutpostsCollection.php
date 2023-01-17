<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Formable;
use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\Outposts\OutpostFilterSettings;
use NMSTracker\Outposts\OutpostSettingsManager;

/**
 * @method OutpostRecord getByID(int $record_id)
 * @method OutpostRecord|NULL getByRequest()
 * @method OutpostFilterCriteria getFilterCriteria()
 * @method OutpostFilterSettings getFilterSettings()
 */
class OutpostsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'outposts';
    public const TABLE_SERVICES = 'outposts_services';
    public const PRIMARY_NAME = 'outpost_id';

    public const COL_LABEL = 'label';
    public const COL_COMMENTS = 'comments';
    public const COL_ROLE_ID = 'outpost_role_id';
    public const COL_PLANET_ID = 'planet_id';
    public const COL_LONGITUDE = 'longitude';
    public const COL_LATITUDE = 'latitude';

    public function getRecordClassName() : string
    {
        return OutpostRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return OutpostFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return OutpostFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return self::TABLE_NAME.'.'.self::COL_LABEL;
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
        return 'outpost';
    }

    public function getCollectionLabel() : string
    {
        return t('Outposts');
    }

    public function getRecordLabel() : string
    {
        return t('Outpost');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    public function createSettingsAdd(Application_Formable $formable, PlanetRecord $planet) : OutpostSettingsManager
    {
        return new OutpostSettingsManager(
            $formable,
            $this,
            $planet
        );
    }

    public function createSettingsEdit(Application_Formable $formable, OutpostRecord $outpost) : OutpostSettingsManager
    {
        return new OutpostSettingsManager(
            $formable,
            $this,
            $outpost->getPlanet(),
            $outpost
        );
    }
}
