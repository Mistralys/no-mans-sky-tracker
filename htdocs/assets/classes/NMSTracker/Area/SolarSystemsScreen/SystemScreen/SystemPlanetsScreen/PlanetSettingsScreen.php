<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionEdit;
use AppUtils\ConvertHelper;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Planets\PlanetSettingsManager;
use NMSTracker\PlanetsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property \NMSTracker\Planets\PlanetRecord $record
 */
class PlanetSettingsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionEdit
    implements ViewPlanetScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;

    public const URL_NAME = 'planet-settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditPlanets();
    }

    public function isEditable() : bool
    {
        return true;
    }

    public function getSettingsManager() : PlanetSettingsManager
    {
        return $this->createCollection()->createSettingsManagerEdit($this, $this->record);
    }

    public function createCollection() : PlanetsCollection
    {
        return ClassFactory::createPlanets();
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The planet %1$s has been updated successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getDefaultFormValues() : array
    {
        $defaults = $this->settingsManager->getDefaultValues();

        $defaults[PlanetSettingsManager::SETTING_RESOURCES] = $this->record->getResourceIDs();

        return $defaults;
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getTitle() : string
    {
        return $this->record->getLabel();
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
