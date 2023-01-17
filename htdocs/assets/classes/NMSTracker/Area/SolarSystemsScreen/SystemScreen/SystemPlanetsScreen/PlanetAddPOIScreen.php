<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionCreate;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetPOIsCollection;

class PlanetAddPOIScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionCreate
    implements ViewPlanetScreenInterface
{
    use ViewPlanetScreenTrait;
    use ViewSystemScreenTrait;

    public const URL_NAME = 'create-poi';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function createCollection() : PlanetPOIsCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManagerAdd($this, $this->getPlanet());
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The point of interest %1$s has been added successfully at %2$s.',
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
        return t('Add a point of interest');
    }
}
