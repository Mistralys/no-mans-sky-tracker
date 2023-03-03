<?php

declare(strict_types=1);

namespace NMSTracker\Area\TagsScreen\TagScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenTrait;
use NMSTracker\Tags\TagRecord;
use NMSTracker\Tags\TagSettingsManager;
use NMSTracker\TagsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property TagRecord $record
 */
class TagSettingsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionEdit
    implements
    ViewSpaceStationScreenInterface
{
    use ViewSpaceStationScreenTrait;

    public const URL_NAME = 'settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditSpaceStations();
    }

    public function isEditable() : bool
    {
        return $this->record->isEditable();
    }

    public function createCollection() : TagsCollection
    {
        return ClassFactory::createTags();
    }

    public function getSettingsManager() : TagSettingsManager
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The tag %1$s has been updated successfully at %2$s.',
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
        return t('Edit a tag');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
