<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\PlanetTypes\PlanetTypeSettingsManager;
use NMSTracker\PlanetTypesCollection;
use NMSTracker_User;

/**
 * @property PlanetTypeRecord $record
 * @property NMSTracker_User $user
 */
class PlanetTypeSettingsScreen extends Application_Admin_Area_Mode_Submode_CollectionEdit
{
    public const URL_NAME = 'settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditPlanetTypes();
    }

    public function isEditable() : bool
    {
        return $this->record->isEditable();
    }

    public function createCollection() : PlanetTypesCollection
    {
        return ClassFactory::createPlanetTypes();
    }

    public function getSettingsManager() :  PlanetTypeSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The planet type %1$s has been updated successfully at %2$s.',
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
        return t('Edit a planet type');
    }
}
