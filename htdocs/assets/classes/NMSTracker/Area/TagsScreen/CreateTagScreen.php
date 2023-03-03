<?php

declare(strict_types=1);

namespace NMSTracker\Area\TagsScreen;

use Application_Admin_Area_Mode_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\TagsCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class CreateTagScreen extends Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'create-tag';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function createCollection() : TagsCollection
    {
        return ClassFactory::createTags();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManager($this, null);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The space station %1$s has been added successfully at %2$s.',
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
        return $this->getUser()->canCreateSpaceStations();
    }

    public function getTitle() : string
    {
        return t('Add a space station');
    }
}
