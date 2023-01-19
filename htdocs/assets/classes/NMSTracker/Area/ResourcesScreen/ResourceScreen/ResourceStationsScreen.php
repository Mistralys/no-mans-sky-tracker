<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen\ResourceScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewResourceScreenInterface;
use NMSTracker\Interfaces\Admin\ViewResourceScreenTrait;
use NMSTracker\ResourcesCollection;
use NMSTracker\SpaceStations\SpaceStationFilterCriteria;
use NMSTracker\SpaceStations\SpaceStationFilterSettings;
use NMSTracker\SpaceStations\SpaceStationRecord;
use NMSTracker\SpaceStationsCollection;
use NMSTracker_User;

/**
 * @property SpaceStationFilterCriteria $filters
 * @property SpaceStationFilterSettings $filterSettings
 * @property NMSTracker_User $user
 */
class ResourceStationsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewResourceScreenInterface
{
    use ViewResourceScreenTrait;

    public const URL_NAME = 'resource-stations';
    public const COL_LABEL = 'label';
    public const COL_OFFER_TYPE = 'offer_type';

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

    protected function configureFilters() : void
    {
        $resource = $this->getResource();

        $this->filters->selectResource($resource);

        $this->filterSettings->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
        $this->grid->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $resource = $this->getResource();
        $station = ClassHelper::requireObjectInstanceOf(
            SpaceStationRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $station->getLabelLinked(),
            self::COL_OFFER_TYPE => $this->renderOffers($station)
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_OFFER_TYPE, t('Offer type'));
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function getNavigationTitle() : string
    {
        return t('Stations');
    }

    public function getTitle() : string
    {
        return t('Space stations by resource');
    }

    private function renderOffers(SpaceStationRecord $station) : string
    {
        $offers = $station->getResourceOffers($this->getResource());
        $items = array();

        foreach($offers as $offer)
        {
            $items[] = $offer->getLabel();
        }

        return implode(', ', $items);
    }
}
