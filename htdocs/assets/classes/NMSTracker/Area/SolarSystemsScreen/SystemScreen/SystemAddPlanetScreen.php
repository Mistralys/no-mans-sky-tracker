<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode_CollectionCreate;
use AppUtils\ClassHelper;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetsCollection;

class SystemAddPlanetScreen
    extends Application_Admin_Area_Mode_Submode_CollectionCreate
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'create-planet';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return PlanetsCollection
     */
    public function createCollection() : PlanetsCollection
    {
        return ClassFactory::createPlanets();
    }

    public function getSettingsManager()
    {
        return $this->createCollection()->createSettingsManagerAdd($this, $this->getSolarSystem());
    }

    public function getSuccessMessage(DBHelper_BaseRecord $record) : string
    {
        return t(
            'The planet %1$s has been added successfully at %2$s.',
            sb()->bold($record->getLabel()),
            sb()->time()
        );
    }

    public function getSuccessURL(DBHelper_BaseRecord $record) : string
    {
        return ClassHelper::requireObjectInstanceOf(
            PlanetRecord::class,
            $record
        )
            ->getAdminStatusURL();
    }

    public function getBackOrCancelURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getTitle() : string
    {
        return t('Add new planet');
    }
}
