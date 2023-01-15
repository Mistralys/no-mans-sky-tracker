<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen;

use Application_Admin_Area_Mode_CollectionCreate;
use AppUtils\ClassHelper;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\PlanetTypesCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class CreatePlanetTypeScreen extends Application_Admin_Area_Mode_CollectionCreate
{
    public const URL_NAME = 'add-planet-type';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }
    public function createCollection() : PlanetTypesCollection
    {
        return ClassFactory::createPlanetTypes();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManager($this, null);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The planet type %1$s has been added successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getSuccessURL(DBHelper_BaseRecord $record) : string
    {
        return ClassHelper::requireObjectInstanceOf(
            PlanetTypeRecord::class,
            $record
        )
            ->getAdminSettingsURL();
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canCreatePlanetTypes();
    }

    public function getTitle() : string
    {
        return t('Add a planet type');
    }
}
