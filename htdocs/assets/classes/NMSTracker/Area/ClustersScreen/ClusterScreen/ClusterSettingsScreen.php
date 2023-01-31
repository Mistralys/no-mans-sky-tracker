<?php

declare(strict_types=1);

namespace NMSTracker\Area\ClustersScreen\ClusterScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Clusters\ClusterRecord;
use NMSTracker\Clusters\ClusterSettingsManager;
use NMSTracker\ClustersCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 * @property ClusterRecord $record
 */
class ClusterSettingsScreen extends Application_Admin_Area_Mode_Submode_CollectionEdit
{
    public const URL_NAME = 'cluster-settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditClusters();
    }

    public function isEditable() : bool
    {
        return $this->record->isEditable();
    }

    public function createCollection() : ClustersCollection
    {
        return ClassFactory::createClusters();
    }

    public function getSettingsManager() : ClusterSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The solar system cluster %1$s has been updated successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getTitle() : string
    {
        return t('Edit a cluster');
    }
}
