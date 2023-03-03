<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Formable;
use Application_Request;
use AppUtils\Interface_Stringable;
use DBHelper_BaseCollection;
use NMSTracker\Area\TagsScreen;
use NMSTracker\Area\TagsScreen\CreateTagScreen;
use NMSTracker\Area\TagsScreen\TagsListScreen;
use NMSTracker\Tags\TagFilterCriteria;
use NMSTracker\Tags\TagFilterSettings;
use NMSTracker\Tags\TagRecord;
use NMSTracker\Tags\TagSettingsManager;

/**
 * @method TagRecord|NULL getByRequest()
 * @method TagRecord getByID(int $record_id)
 * @method TagRecord[] getAll()
 * @method TagFilterSettings getFilterSettings()
 * @method TagFilterCriteria getFilterCriteria()
 */
class TagsCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'tags';
    public const PRIMARY_NAME = 'tag_id';

    public const COL_LABEL = 'label';

    public function getRecordClassName() : string
    {
        return TagRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return TagFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return TagFilterSettings::class;
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
        return 'tag';
    }

    public function getCollectionLabel() : string
    {
        return t('Tags');
    }

    public function getRecordLabel() : string
    {
        return t('Tag');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    public function createSettingsManager(Application_Formable $formable, ?TagRecord $record) : TagSettingsManager
    {
        return new TagSettingsManager($formable, $this, $record);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminListURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = TagsListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminCreateURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = CreateTagScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|Interface_Stringable|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = TagsScreen::URL_NAME;

        return Application_Request::getInstance()->buildURL($params);
    }
}
