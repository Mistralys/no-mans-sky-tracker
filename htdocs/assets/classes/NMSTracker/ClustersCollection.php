<?php

declare(strict_types=1);

namespace NMSTracker;

use Application_Admin_ScreenInterface;
use Application_Formable;
use Application_Request;
use DBHelper_BaseCollection;
use NMSTracker;
use NMSTracker\Area\ClustersScreen;
use NMSTracker\Area\ClustersScreen\ClustersListScreen;
use NMSTracker\Area\ClustersScreen\CreateClusterScreen;
use NMSTracker\Clusters\ClusterFilterCriteria;
use NMSTracker\Clusters\ClusterFilterSettings;
use NMSTracker\Clusters\ClusterRecord;
use NMSTracker\Clusters\ClusterSettingsManager;
use UI_Icon;

/**
 * @method ClusterRecord[] getAll()
 * @method ClusterRecord getByID(int $record_id)
 * @method ClusterRecord|NULL getByRequest()
 * @method ClusterFilterCriteria getFilterCriteria()
 * @method ClusterFilterSettings getFilterSettings()
 */
class ClustersCollection extends DBHelper_BaseCollection
{
    public const TABLE_NAME = 'clusters';
    public const PRIMARY_NAME = 'cluster_id';

    public const COL_LABEL = 'label';
    public const COL_COMMENTS = 'comments';
    public const COL_CORE_DISTANCE = 'core_distance';

    public function getRecordClassName() : string
    {
        return ClusterRecord::class;
    }

    public function getRecordFiltersClassName() : string
    {
        return ClusterFilterCriteria::class;
    }

    public function getRecordFilterSettingsClassName() : string
    {
        return ClusterFilterSettings::class;
    }

    public function getRecordDefaultSortKey() : string
    {
        return '`'.self::TABLE_NAME.'`.`'.self::COL_LABEL.'`';
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
        return 'cluster';
    }

    public function getCollectionLabel() : string
    {
        return t('Solar system clusters');
    }

    public function getRecordLabel() : string
    {
        return t('Solar system cluster');
    }

    public function getRecordProperties() : array
    {
        return array();
    }

    public function getIcon() : UI_Icon
    {
        return NMSTracker::icon()->cluster();
    }

    /**
     * @param array<string,string|number|NULL> $params
     * @return string
     */
    public function getAdminListURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = ClustersListScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|NULL> $params
     * @return string
     */
    public function getAdminCreateURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = CreateClusterScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = ClustersScreen::URL_NAME;

        return Application_Request::getInstance()
            ->buildURL($params);
    }

    public function createSettingsManager(Application_Formable $formable, ?ClusterRecord $record) : ClusterSettingsManager
    {
        return new ClusterSettingsManager($formable, $this, $record);
    }
}
