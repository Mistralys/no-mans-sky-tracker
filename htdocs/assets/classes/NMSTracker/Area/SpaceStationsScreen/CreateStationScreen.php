<?php

declare(strict_types=1);

namespace NMSTracker\Area\SpaceStationsScreen;

use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\SpaceStationsCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class CreateStationScreen extends \Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'create-station';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function createCollection() : SpaceStationsCollection
    {
        return ClassFactory::createSpaceStations();
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
