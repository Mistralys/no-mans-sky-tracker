<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionEdit;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewPOIScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPOIScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetPOIs\PlanetPOISettingsManager;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property PlanetPOIRecord $record
 */
class PlanetPOISettingsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionEdit
    implements ViewPOIScreenInterface
{
    use ViewPlanetScreenTrait;
    use ViewSystemScreenTrait;
    use ViewPOIScreenTrait;

    public const URL_NAME = 'poi-settings';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->user->canEditPOIs();
    }

    public function isEditable() : bool
    {
        return $this->record->isEditable();
    }

    public function createCollection() : PlanetPOIsCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    public function getSettingsManager() : PlanetPOISettingsManager
    {
        return $this->createCollection()->createSettingsManagerEdit($this, $this->record);
    }

    protected function _handleHiddenVars() : void
    {
        $this->addHiddenVar(
            SolarSystemsCollection::PRIMARY_NAME,
            (string)$this->record->getSolarSystem()->getID()
        );

        $this->addHiddenVar(
            PlanetsCollection::PRIMARY_NAME,
            (string)$this->record->getPlanetID()
        );
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The point of interest %1$s has been updated successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getPlanet()->getAdminPOIsURL();
    }

    public function getTitle() : string
    {
        return t('Point of interest settings');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
