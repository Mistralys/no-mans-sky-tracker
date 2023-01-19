<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use DBHelper_BaseFilterSettings;
use NMSTracker\ClassFactory;

/**
 * @property ResourceFilterCriteria $filters
 */
class ResourceFilterSettings extends DBHelper_BaseFilterSettings
{
    public const SETTING_SEARCH = 'search';
    public const SETTING_TYPE = 'type';
    public const SETTING_STATION_COMMODITIES = 'station_commodities';
    public const COMMODITIES_BOTH = 'both';
    public const COMMODITIES_BOUGHT = 'bought';
    public const COMMODITIES_SOLD = 'sold';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_TYPE, t('Type'));
        $this->registerSetting(self::SETTING_STATION_COMMODITIES, t('Station commodities'));
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);
        $this->configureType($this->getSettingString(self::SETTING_TYPE));
        $this->configureStationCommodities($this->getSettingString(self::SETTING_STATION_COMMODITIES));
    }

    protected function inject_station_commodities() : void
    {
        $el = $this->addElementSelect(self::SETTING_STATION_COMMODITIES);

        $el->addOption(t('Any'), '');

        $el->addOption(t('Bought or sold'), self::COMMODITIES_BOTH);
        $el->addOption(t('Only bought'), self::COMMODITIES_BOUGHT);
        $el->addOption(t('Only sold'), self::COMMODITIES_SOLD);
    }

    protected function inject_type() : void
    {
        $el = $this->addElementSelect(self::SETTING_TYPE);

        $el->addOption(t('Any'), '');

        $types = ClassFactory::createResourceTypes()->getAll();

        foreach($types as $type)
        {
            $el->addOption($type->getLabel(), $type->getID());
        }
    }

    private function configureType(string $typeID) : void
    {
        $collection = ClassFactory::createResourceTypes();

        if(empty($typeID) || !$collection->idExists($typeID))
        {
            return;
        }

        $this->filters->selectResourceType($collection->getByID($typeID));
    }

    private function configureStationCommodities(string $type) : void
    {
        if(empty($type)) {
            return;
        }

        $offerTypes = ClassFactory::createSpaceStations()->getOfferTypes();

        if($type === self::COMMODITIES_BOTH)
        {
            $this->filters->selectStationOfferType($offerTypes->getBuyOfferType());
            $this->filters->selectStationOfferType($offerTypes->getSellOfferType());
        }
        else if($type === self::COMMODITIES_BOUGHT)
        {
            $this->filters->selectStationOfferType($offerTypes->getBuyOfferType());
        }
        else if ($type === self::COMMODITIES_SOLD)
        {
            $this->filters->selectStationOfferType($offerTypes->getSellOfferType());
        }
    }
}
