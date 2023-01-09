<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen;

use Application_Admin_Area_Mode_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\SolarSystems\SystemSettingsManager;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class CreateSystemScreen extends Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'add-system';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getSettingsManager() : SystemSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this);
    }

    public function createCollection() : SolarSystemsCollection
    {
        return ClassFactory::createSolarSystems();
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The solar system %1$s has been added successfully at %2$s.',
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
        return $this->user->canCreateSolarSystems();
    }

    public function getTitle() : string
    {
        return t('Add a solar system');
    }
}
