<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen;

use Application_Admin_Area_Mode_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Resources\ResourceSettingsManager;
use NMSTracker\ResourcesCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class CreateResourceScreen extends Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'create-resource';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function createCollection() : ResourcesCollection
    {
        return ClassFactory::createResources();
    }

    public function getSettingsManager() : ResourceSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, null);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The resource %1$s has been added successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canCreateResources();
    }

    public function getTitle() : string
    {
        return t('Add a resource');
    }
}
