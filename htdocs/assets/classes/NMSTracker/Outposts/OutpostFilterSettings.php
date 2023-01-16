<?php

declare(strict_types=1);

namespace NMSTracker\Outposts;

use Application_Exception_DisposableDisposed;
use DBHelper_BaseFilterSettings;
use DBHelper_Exception;
use NMSTracker\ClassFactory;

/**
 * @property OutpostFilterCriteria $filters
 */
class OutpostFilterSettings extends DBHelper_BaseFilterSettings
{
    public const SETTING_SEARCH = 'search';
    public const SETTING_ROLE = 'role';
    public const SETTING_SERVICES = 'services';
    public const SETTING_SENTINEL_LEVEL = 'sentinels';

    protected function registerSettings() : void
    {
        $this->registerSetting(self::SETTING_SEARCH, t('Search'));
        $this->registerSetting(self::SETTING_ROLE, t('Role'));
        $this->registerSetting(self::SETTING_SERVICES, t('Services'));
        $this->registerSetting(self::SETTING_SENTINEL_LEVEL, t('Sentinel level'));
    }

    protected function inject_role() : void
    {
        $el = $this->addElementSelect(self::SETTING_ROLE);

        $el->addOption(t('Any'), '');

        $items = ClassFactory::createOutpostRoles()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }
    }

    protected function inject_services() : void
    {
        $el = $this->addElementSelect(self::SETTING_SERVICES);
        $el->setAttribute('multiple', 'multiple');
        $el->setAttribute('size', 6);
        $el->setComment(t('When several are selected, only outposts that have all selected services will be shown.'));

        $items = ClassFactory::createOutpostServices()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }
    }

    protected function inject_sentinels() : void
    {
        $el = $this->addElementSelect(self::SETTING_SENTINEL_LEVEL);

        $el->addOption(t('Any'), '');

        $levels = ClassFactory::createSentinelLevels()->getAll();

        foreach($levels as $level)
        {
            $el->addOption($level->getLabel(), (string)$level->getID());
        }
    }

    protected function _configureFilters() : void
    {
        $this->configureSearch(self::SETTING_SEARCH);
        $this->configureRole($this->getSettingInt(self::SETTING_ROLE));
        $this->configureServices($this->getArraySetting(self::SETTING_SERVICES));
        $this->configureSentinelLevel($this->getSettingInt(self::SETTING_SENTINEL_LEVEL));
    }

    private function configureRole(int $roleID) : void
    {
        $collection = ClassFactory::createOutpostRoles();

        if($roleID === 0 || !$collection->idExists($roleID))
        {
            return;
        }

        $this->filters->selectRole($collection->getByID($roleID));
    }

    /**
     * @param array<int,int|string> $serviceIDs
     * @return void
     * @throws Application_Exception_DisposableDisposed
     * @throws DBHelper_Exception
     */
    private function configureServices(array $serviceIDs) : void
    {
        $collection = ClassFactory::createOutpostServices();

        foreach($serviceIDs as $serviceID)
        {
            $serviceID = (int)$serviceID;

            if($collection->idExists($serviceID)) {
                $this->filters->selectService($collection->getByID($serviceID));
            }
        }
    }

    private function configureSentinelLevel(int $levelID) : void
    {
        $collection = ClassFactory::createSentinelLevels();

        if($levelID === 0 || !$collection->idExists($levelID))
        {
            return;
        }

        $this->filters->selectSentinelLevel($collection->getByID($levelID));
    }
}
