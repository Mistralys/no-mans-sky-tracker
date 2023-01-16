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

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_TYPE, t('Type'));
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);
        $this->configureType($this->getSettingString(self::SETTING_TYPE));
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

        $this->filters->selectType($collection->getByID($typeID));
    }
}
