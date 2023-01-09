<?php

declare(strict_types=1);

namespace NMSTracker\SentinelLevels;

use DBHelper_BaseFilterSettings;

class SentinelLevelFilterSettings extends DBHelper_BaseFilterSettings
{
    public const SETTING_SEARCH = 'search';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);
    }
}
