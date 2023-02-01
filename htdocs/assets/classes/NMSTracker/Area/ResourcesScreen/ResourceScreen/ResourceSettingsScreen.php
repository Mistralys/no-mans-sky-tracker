<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen\ResourceScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewResourceScreenInterface;
use NMSTracker\Interfaces\Admin\ViewResourceScreenTrait;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\Resources\ResourceSettingsManager;
use NMSTracker\ResourcesCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property ResourceRecord $record
 */
class ResourceSettingsScreen extends Application_Admin_Area_Mode_Submode_CollectionEdit
    implements ViewResourceScreenInterface
{
    use ViewResourceScreenTrait;

    public const URL_NAME = 'resource-settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditResources();
    }

    public function isEditable() : bool
    {
        return $this->record->isEditable();
    }

    public function createCollection() : ResourcesCollection
    {
        return ClassFactory::createResources();
    }

    public function getSettingsManager() : ResourceSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The resource %1$s has been updated successfully at %2$s.',
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
        return t('Edit a resource');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
