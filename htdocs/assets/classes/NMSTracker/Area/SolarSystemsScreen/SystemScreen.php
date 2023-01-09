<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen;

use Application_Admin_Area_Mode_CollectionRecord;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemStatusScreen;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;
use UI;

/**
 * @property NMSTracker_User $user
 * @method SolarSystemRecord getRecord()
 */
class SystemScreen extends Application_Admin_Area_Mode_CollectionRecord
{
    public const URL_NAME = 'view';

    protected function createCollection() : SolarSystemsCollection
    {
        return NMSTracker\ClassFactory::createSolarSystems();
    }

    protected function getRecordMissingURL() : string
    {
        return $this->createCollection()->getAdminListURL();
    }

    public function getDefaultSubmode() : string
    {
        return SystemStatusScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewSolarSystems();
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('View');
    }

    public function getTitle() : string
    {
        return t('View solar system');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setText($this->getRecord()->getLabel())
            ->setIcon(NMSTracker::icon()->solarSystem());
    }

    protected function _handleBreadcrumb() : void
    {
        $this->breadcrumb->appendArea($this->area);
        $this->breadcrumb->appendItem($this->getRecord()->getLabel())->makeLinked($this->getRecord()->getAdminViewURL());
    }

    protected function _handleSubnavigation() : void
    {
        $record = $this->getRecord();

        $this->subnav->addURL(
            t('Status'),
            $record->getAdminStatusURL()
        )
            ->setIcon(UI::icon()->status());

        $this->subnav->addURL(
            t('Planets'),
            $record->getAdminPlanetsURL()
        )
            ->setIcon(NMSTracker::icon()->planet());

        $this->subnav->addURL(
            t('Outposts'),
            $record->getAdminOutpostsURL()
        )
            ->setIcon(NMSTracker::icon()->outpost());

        $this->subnav->addURL(
            t('Resources'),
            $record->getAdminResourcesURL()
        )
            ->setIcon(NMSTracker::icon()->resources());

        $this->subnav->addURL(
            t('Settings'),
            $record->getAdminSettingsURL()
        )
            ->setIcon(UI::icon()->settings());
    }
}
