<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use Application_Exception;
use Application_Exception_DisposableDisposed;
use DBHelper_BaseFilterSettings;
use DBHelper_Exception;
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
    public const SETTING_PLANET_FALL = 'planet_fall';
    public const PLANET_FALL_MODE_MADE = 'planet_fall_made';
    public const PLANET_FALL_MODE_NOT_MADE = 'planet_fall_not_made';
    public const SETTING_RATING = 'rating';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_RATING, t('Rating'));
        $this->registerSetting(self::SETTING_SCAN_COMPLETE, t('Scan complete?'));
        $this->registerSetting(self::SETTING_SENTINELS, t('Sentinels level'));
        $this->registerSetting(self::SETTING_TYPE, t('Planet type'));
        $this->registerSetting(self::SETTING_PLANET_FALL, t('Planet-fall made?'));
    }

    protected function inject_rating() : void
    {
        $el = $this->addElementSelect(self::SETTING_RATING);

        $el->addOption(t('Any'), '');

        $ratings = PlanetRatings::getInstance()->getAll();

        foreach($ratings as $rating)
        {
            $el->addOption($rating->getLabelForSelect(), $rating->getID());
        }
    }

    protected function inject_scan_complete() : void
    {
        $el = $this->addElementSelect(self::SETTING_SCAN_COMPLETE);

        $el->addOption(t('Any'), '');
        $el->addOption(t('Complete only'), self::SCAN_MODE_COMPLETE);
        $el->addOption(t('Incomplete only'), self::SCAN_MODE_INCOMPLETE);
    }

    protected function inject_planet_fall() : void
    {
        $el = $this->addElementSelect(self::SETTING_PLANET_FALL);

        $el->addOption(t('Any'), '');
        $el->addOption(t('Planetfall made'), self::PLANET_FALL_MODE_MADE);
        $el->addOption(t('Planetfall not made'), self::PLANET_FALL_MODE_NOT_MADE);
    }

    protected function inject_sentinels() : void
    {
        $el = $this->addElementSelect(self::SETTING_SENTINELS);

        $el->addOption(t('Any'), '');

        $group = $el->addOptgroup(t('Aggression level'));

        $items = ClassFactory::createSentinelAggressionLevels()->getAll();
        foreach($items as $item)
        {
            $group->addOption($item->getLabel(), $item->getID());
        }

        $items = ClassFactory::createSentinelLevels()->getAll();
        $group = $el->addOptgroup(t('Specific level'));

        foreach($items as $item)
        {
            $group->addOption($item->getLabel(), (string)$item->getID());
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
        $this->configureSentinels($this->getSettingString(self::SETTING_SENTINELS));
        $this->configureType($this->getSettingInt(self::SETTING_TYPE));
        $this->configurePlanetFall($this->getSettingString(self::SETTING_PLANET_FALL));
        $this->configureRating($this->getSettingString(self::SETTING_RATING));
    }

    private function configureRating(string $ratingID) : void
    {
        $collection = PlanetRatings::getInstance();

        if(!empty($ratingID) && $collection->idExists($ratingID))
        {
            $this->filters->selectRating($collection->getByID($ratingID));
        }
    }

    private function configurePlanetFall(string $mode) : void
    {
        if($mode === self::PLANET_FALL_MODE_MADE)
        {
            $this->filters->selectPlanetFallMade(true);
        }
        else if($mode === self::PLANET_FALL_MODE_NOT_MADE)
        {
            $this->filters->selectPlanetFallMade(false);
        }
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

    /**
     * @param string|int $sentinelLevelID
     * @return void
     * @throws Application_Exception
     * @throws Application_Exception_DisposableDisposed
     * @throws DBHelper_Exception
     */
    private function configureSentinels($sentinelLevelID) : void
    {
        $aggressionLevels = ClassFactory::createSentinelAggressionLevels();

        if($aggressionLevels->idExists($sentinelLevelID))
        {
            $this->filters->selectSentinelAggressionLevel($aggressionLevels->getByID($sentinelLevelID));
            return;
        }

        $specificLevels = ClassFactory::createSentinelLevels();
        $sentinelLevelID = (int)$sentinelLevelID;

        if($sentinelLevelID === 0 || !$specificLevels->idExists($sentinelLevelID))
        {
            return;
        }

        $this->filters->selectSentinelLevel($specificLevels->getByID($sentinelLevelID));
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
