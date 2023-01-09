<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property SolarSystemRecord $record
 */
class SystemSettingsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionEdit
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditSolarSystems();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function isEditable() : bool
    {
        return true;
    }

    public function createCollection() : SolarSystemsCollection
    {
        return ClassFactory::createSolarSystems();
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The solar system %1$s has been updated successfully at %2$s.',
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
        return t('Edit a solar system');
    }
}
