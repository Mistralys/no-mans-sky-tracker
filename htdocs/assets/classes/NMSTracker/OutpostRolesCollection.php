<?php

declare(strict_types=1);

namespace NMSTracker;

use DBHelper_BaseCollection;
use NMSTracker\OutpostRoles\OutpostRoleFilterCriteria;
use NMSTracker\OutpostRoles\OutpostRoleFilterSettings;
use NMSTracker\OutpostRoles\OutpostRoleRecord;

/**
 * @method OutpostRoleRecord getByID(int $record_id)
 * @method OutpostRoleRecord|NULL getByRequest()
 * @method OutpostRoleRecord[] getAll()
 * @method OutpostRoleFilterSettings getFilterSettings()
 * @method OutpostRoleFilterCriteria getFilterCriteria()
 */
class OutpostRolesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'outpost_roles';
    public const PRIMARY_NAME = 'outpost_role_id';

    public const COL_LABEL = 'label';

    public function getRecordClassName() : string
    {
        return OutpostRoleRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return OutpostRoleFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return OutpostRoleFilterSettings::class;
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
        return 'outpost_role';
    }

    public function getCollectionLabel() : string
    {
        return t('Outpost roles');
    }

    public function getRecordLabel() : string
    {
        return t('Outpost role');
    }

    public function getRecordProperties() : array
    {
        return array();
    }
}
