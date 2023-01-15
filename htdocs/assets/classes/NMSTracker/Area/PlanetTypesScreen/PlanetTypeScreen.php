<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen;

use Application_Admin_Area_Mode_CollectionRecord;
use NMSTracker;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen\PlanetTypeStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\PlanetTypesCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 * @property PlanetTypeRecord $record
 */
class PlanetTypeScreen extends Application_Admin_Area_Mode_CollectionRecord
{
    public const URL_NAME = 'view';

    protected function createCollection() : PlanetTypesCollection
    {
        return ClassFactory::createPlanetTypes();
    }

    protected function getRecordMissingURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getDefaultSubmode() : string
    {
        return PlanetTypeStatusScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPlanetTypes();
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Details');
    }

    public function getTitle() : string
    {
        return t('View planet type');
    }

    protected function _handleBreadcrumb() : void
    {
        $this->breadcrumb->appendItem(t('Planet types'))
            ->makeLinked($this->createCollection()->getAdminListURL());
    }

    protected function _handleSubnavigation() : void
    {
        $this->subnav->addURL(
            t('Status'),
            $this->record->getAdminStatusURL()
        )
            ->setIcon(NMSTracker::icon()->status());

        $this->subnav->addURL(
            t('Planets'),
            $this->record->getAdminPlanetsURL()
        )
            ->setIcon(NMSTracker::icon()->planet());

        $this->subnav->addURL(
            t('Settings'),
            $this->record->getAdminSettingsURL()
        )
            ->setIcon(NMSTracker::icon()->settings());
    }
}
