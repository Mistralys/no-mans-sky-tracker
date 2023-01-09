<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use DBHelper_BaseFilterSettings;
use NMSTracker\ClassFactory;

/**
 * @property PlanetFilterCriteria $filters
 */
class PlanetFilterSettings extends DBHelper_BaseFilterSettings
{
    public const SETTING_SEARCH = 'search';
    public const SETTING_SCAN_COMPLETE = 'scan_complete';
    public const SETTING_SENTINELS = 'sentinels';
    public const SETTING_TYPE = 'type';
    public const SCAN_MODE_COMPLETE = 'complete';
    public const SCAN_MODE_INCOMPLETE = 'incomplete';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_SCAN_COMPLETE, t('Scan complete?'));
        $this->registerSetting(self::SETTING_SENTINELS, t('Sentinels level'));
        $this->registerSetting(self::SETTING_TYPE, t('Planet type'));
    }

    protected function inject_scan_complete() : void
    {
        $el = $this->addElementSelect(self::SETTING_SCAN_COMPLETE);

        $el->addOption(t('Any'), '');
        $el->addOption(t('Complete only'), self::SCAN_MODE_COMPLETE);
        $el->addOption(t('Incomplete only'), self::SCAN_MODE_INCOMPLETE);
    }

    protected function inject_sentinels() : void
    {
        $el = $this->addElementSelect(self::SETTING_SENTINELS);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createSentinelLevels()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }
    }

    protected function inject_type() : void
    {
        $el = $this->addElementSelect(self::SETTING_TYPE);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createPlanetTypes()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);

        $this->configureScanCompleted($this->getSettingString(self::SETTING_SCAN_COMPLETE));
        $this->configureSentinels($this->getSettingInt(self::SETTING_SENTINELS));
        $this->configureType($this->getSettingInt(self::SETTING_TYPE));
    }

    private function configureScanCompleted(string $value) : void
    {
        if($value === self::SCAN_MODE_COMPLETE)
        {
            $this->filters->selectScanComplete(true);
        }
        else if($value === self::SCAN_MODE_INCOMPLETE)
        {
            $this->filters->selectScanComplete(false);
        }
    }

    private function configureSentinels(int $sentinelLevelID) : void
    {
        $collection = ClassFactory::createSentinelLevels();

        if($sentinelLevelID === 0 || !$collection->idExists($sentinelLevelID))
        {
            return;
        }

        $this->filters->selectSentinelLevel($collection->getByID($sentinelLevelID));
    }

    private function configureType(int $planetTypeID) : void
    {
        $collection = ClassFactory::createPlanetTypes();

        if($planetTypeID === 0 || !$collection->idExists($planetTypeID))
        {
            return;
        }

        $this->filters->selectPlanetType($collection->getByID($planetTypeID));
    }
}
