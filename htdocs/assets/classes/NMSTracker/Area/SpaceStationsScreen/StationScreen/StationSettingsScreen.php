<?php

declare(strict_types=1);

namespace NMSTracker\Area\SpaceStationsScreen\StationScreen;

use Application_Admin_Area_Mode_Submode_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenTrait;
use NMSTracker\SpaceStations\SpaceStationRecord;
use NMSTracker\SpaceStations\SpaceStationSettingsManager;
use NMSTracker\SpaceStationsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property SpaceStationRecord $record
 */
class StationSettingsScreen
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

    public function createCollection() : SpaceStationsCollection
    {
        return ClassFactory::createSpaceStations();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManager($this, $this->record);
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The space station %1$s has been updated successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getDefaultFormValues() : array
    {
        $values = parent::getDefaultFormValues();

        $values[SpaceStationSettingsManager::SETTING_BUY_OFFERS] = $this->record->getBuyOfferIDs();
        $values[SpaceStationSettingsManager::SETTING_SELL_OFFERS] = $this->record->getSellOfferIDs();

        return $values;
    }

    public function getTitle() : string
    {
        return t('Edit a space station');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
