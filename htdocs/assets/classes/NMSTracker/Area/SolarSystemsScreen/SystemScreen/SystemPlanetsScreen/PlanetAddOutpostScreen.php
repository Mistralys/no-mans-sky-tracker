<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Outposts\OutpostSettingsManager;
use NMSTracker\OutpostsCollection;

class PlanetAddOutpostScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionCreate
    implements ViewPlanetScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;

    public const URL_NAME = 'add-outpost';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function createCollection() : OutpostsCollection
    {
        return ClassFactory::createOutposts();
    }

    public function getSettingsManager() : OutpostSettingsManager
    {
        return $this->createCollection()
            ->createSettingsAdd($this, $this->getPlanet());
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The outpost %1$s has been added successfully at %2$s.',
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
        return t('Add an outpost');
    }

    protected function resolveTitle() : string
    {
        return '';
    }
}
