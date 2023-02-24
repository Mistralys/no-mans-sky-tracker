<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Driver;
use Application_Formable;
use AppUtils\Interface_Stringable;
use DBHelper_BaseCollection;
use NMSTracker;
use NMSTracker\Area\PlanetTypesScreen;
use NMSTracker\Area\PlanetTypesScreen\CreatePlanetTypeScreen;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypesListScreen;
use NMSTracker\PlanetTypes\PlanetTypeFilterCriteria;
use NMSTracker\PlanetTypes\PlanetTypeFilterSettings;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\PlanetTypes\PlanetTypeSettingsManager;
use UI_Icon;

/**
 * @method PlanetTypeRecord getByID(int $record_id)
 * @method PlanetTypeRecord[] getAll()
 * @method PlanetTypeRecord|NULL getByRequest()
 * @method PlanetTypeFilterSettings getFilterSettings()
 * @method PlanetTypeFilterCriteria getFilterCriteria()
 */
class PlanetTypesCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'planet_types';
    public const PRIMARY_NAME = 'planet_type_id';

    public const COL_LABEL = 'label';
    public const COL_COMMENTS = 'comments';

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

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminListURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = PlanetTypesListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminCreateURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = CreatePlanetTypeScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = PlanetTypesScreen::URL_NAME;

        return Application_Driver::getInstance()
            ->getRequest()
            ->buildURL($params);
    }

    public function createSettingsManager(Application_Formable $formable, ?PlanetTypeRecord $record) : PlanetTypeSettingsManager
    {
        return new PlanetTypeSettingsManager($formable, $this, $record);
    }

    public function getIcon() : UI_Icon
    {
        return NMSTracker::icon()->planetType();
    }
}
