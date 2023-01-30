<?php

declare(strict_types=1);

namespace NMSTracker\Area\ClustersScreen;

use Application_Admin_Area_Mode_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Clusters\ClusterSettingsManager;
use NMSTracker\ClustersCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class CreateClusterScreen extends Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'create-cluster';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return ClustersCollection
     */
    public function createCollection() : ClustersCollection
    {
        return ClassFactory::createClusters();
    }

    public function getSettingsManager() : ClusterSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, null);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The solar system cluster %1$s has been created successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canCreateClusters();
    }

    public function getTitle() : string
    {
        return t('Add a solar system cluster');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon($this->createCollection()->getIcon());
    }
}
