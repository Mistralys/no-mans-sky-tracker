<?php

declare(strict_types=1);

namespace NMSTracker\Area\SpaceStationsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\SpaceStations\SpaceStationFilterCriteria;
use NMSTracker\SpaceStations\SpaceStationFilterSettings;
use NMSTracker\SpaceStations\SpaceStationRecord;
use NMSTracker\SpaceStationsCollection;
use NMSTracker_User;
use UI;

/**
 * @property SpaceStationFilterCriteria $filters
 * @property SpaceStationFilterSettings $filterSettings
 * @property NMSTracker_User $user
 */
class StationsListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';
    public const COL_LABEL = 'label';
    public const COL_SOLAR_SYSTEM = 'solar_system';
    public const COL_SALE_OFFERS = 'sale_offers';
    public const COL_BUY_OFFERS = 'buy_offers';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return SpaceStationsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createSpaceStations();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $station = ClassHelper::requireObjectInstanceOf(
            SpaceStationRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $station->getLabelLinked(),
            self::COL_SOLAR_SYSTEM => $station->getSolarSystem()->getLabelLinked(),
            self::COL_SALE_OFFERS => count($station->getSellOfferIDs()),
            self::COL_BUY_OFFERS => count($station->getBuyOfferIDs())
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SOLAR_SYSTEM, t('Solar system'));

        $this->grid->addColumn(self::COL_SALE_OFFERS, t('Sell offers'));

        $this->grid->addColumn(self::COL_BUY_OFFERS, t('Buy offers'));
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_station', t('Add a station...'))
            ->setIcon(UI::icon()->add())
            ->makeLinked($this->createCollection()->getAdminCreateURL());

        parent::_handleSidebar();
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->spaceStation());
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewSpaceStations();
    }

    public function getNavigationTitle() : string
    {
        return t('Space stations');
    }

    public function getTitle() : string
    {
        return t('Space stations');
    }
}
