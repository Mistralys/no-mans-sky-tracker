<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionEdit;
use NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewOutpostScreenInterface;
use NMSTracker\Interfaces\Admin\ViewOutpostScreenTrait;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Outposts\OutpostSettingsManager;
use NMSTracker\OutpostsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;

/**
 * @property \NMSTracker\Outposts\OutpostRecord $record
 * @property NMSTracker_User $user
 */
class OutpostSettingsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionEdit
    implements ViewOutpostScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;
    use ViewOutpostScreenTrait;

    public const URL_NAME = 'outpost-settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultFormValues() : array
    {
        $defaults = $this->settingsManager->getDefaultValues();

        $defaults[OutpostSettingsManager::SETTING_SERVICES] = $this->record->getServiceIDs();

        return $defaults;
    }

    public function getSettingsManager() : OutpostSettingsManager
    {
        return $this->createCollection()
            ->createSettingsEdit($this, $this->record);
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditOutposts();
    }

    public function isEditable() : bool
    {
        return true;
    }

    public function createCollection() : OutpostsCollection
    {
        return ClassFactory::createOutposts();
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The outpost %1$s has been updated successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getPlanet()->getAdminOutpostsURL();
    }

    public function getTitle() : string
    {
        return t('Edit an outpost');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}