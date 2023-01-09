<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use DBHelper_BaseFilterSettings;
use NMSTracker\ClassFactory;

/**
 * @property SolarSystemFilterCriteria $filters
 */
class SolarSystemFilterSettings extends DBHelper_BaseFilterSettings
{
    public const SETTING_SEARCH = 'search';
    public const SETTING_STAR_TYPE = 'star';
    public const SETTING_RACE = 'race';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_STAR_TYPE, t('Star type'));
        $this->registerSetting(self::SETTING_RACE, t('Dominant race'));
    }

    protected function inject_star() : void
    {
        $el = $this->addElementSelect(self::SETTING_STAR_TYPE);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createStarTypes()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), $item->getID());
        }
    }

    protected function inject_race() : void
    {
        $el = $this->addElementSelect(self::SETTING_RACE);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createRaces()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), $item->getID());
        }
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);

        $this->configureStarType($this->getSettingInt(self::SETTING_STAR_TYPE));
        $this->configureRace($this->getSettingInt(self::SETTING_RACE));
    }

    private function configureStarType(int $typeID) : void
    {
        $collection = ClassFactory::createStarTypes();

        if($typeID === 0 || !$collection->idExists($typeID))
        {
            return;
        }

        $this->filters->selectStarType($collection->getByID($typeID));
    }

    private function configureRace(int $raceID) : void
    {
        $collection = ClassFactory::createRaces();

        if($raceID === 0 || !$collection->idExists($raceID)) {
            return;
        }

        $this->filters->selectRace($collection->getByID($raceID));
    }
}
