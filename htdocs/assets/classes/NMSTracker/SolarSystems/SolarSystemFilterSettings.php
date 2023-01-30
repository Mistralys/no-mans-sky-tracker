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
    public const SETTING_OWN_DISCOVERY = 'own_discovery';
    public const DISCOVERY_TYPE_OWN = 'only_own';
    public const DISCOVERY_TYPE_OTHERS = 'only_others';
    public const SETTING_CLUSTER = 'cluster';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_STAR_TYPE, t('Star type'));
        $this->registerSetting(self::SETTING_RACE, t('Dominant race'));
        $this->registerSetting(self::SETTING_OWN_DISCOVERY, t('Own discovery?'));
        $this->registerSetting(self::SETTING_CLUSTER, t('Cluster'));
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

    protected function inject_cluster() : void
    {
        $el = $this->addElementSelect(self::SETTING_CLUSTER);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createClusters()->getAll();

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

    protected function inject_own_discovery() : void
    {
        $el = $this->addElementSelect(self::SETTING_OWN_DISCOVERY);

        $el->addOption(t('Any'), '');
        $el->addOption(t('Only my own discoveries'), self::DISCOVERY_TYPE_OWN);
        $el->addOption(t('Only other people\'s discoveries'), self::DISCOVERY_TYPE_OTHERS);
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);

        $this->configureStarType($this->getSettingInt(self::SETTING_STAR_TYPE));
        $this->configureRace($this->getSettingInt(self::SETTING_RACE));
        $this->configureDiscoveries($this->getSettingString(self::SETTING_OWN_DISCOVERY));
        $this->configureCluster($this->getSettingInt(self::SETTING_CLUSTER));
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

    private function configureDiscoveries(string $discoveryMode) : void
    {
        if($discoveryMode === self::DISCOVERY_TYPE_OWN)
        {
            $this->filters->selectOwnDiscoveries(true);
        }
        else if($discoveryMode === self::DISCOVERY_TYPE_OTHERS)
        {
            $this->filters->selectOwnDiscoveries(false);
        }
    }

    private function configureCluster(int $clusterID) : void
    {
        $collection = ClassFactory::createClusters();

        if($clusterID === 0 || !$collection->idExists($clusterID))
        {
            return;
        }

        $this->filters->selectCluster($collection->getByID($clusterID));
    }
}
