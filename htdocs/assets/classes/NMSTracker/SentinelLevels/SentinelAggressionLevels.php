<?php

declare(strict_types=1);

namespace NMSTracker\SentinelLevels;

use NMSTracker\SentinelLevelsCollection;
use NMSTracker\TrackerException;

class SentinelAggressionLevels
{
    public const LEVEL_GRAY = 'gray';
    public const LEVEL_ORANGE = 'orange';
    public const LEVEL_RED = 'red';
    public const LEVEL_NONE = 'none';

    /**
     * @var array<string,SentinelAggressionLevel>
     */
    private array $levels = array();
    private static ?SentinelAggressionLevels $instance = null;

    private function __construct()
    {
        $this->registerLevel(self::LEVEL_NONE, t('No sentinel presence'));
        $this->registerLevel(self::LEVEL_GRAY, t('Mostly harmless'));
        $this->registerLevel(self::LEVEL_ORANGE, t('Annoying'));
        $this->registerLevel(self::LEVEL_RED, t('Dangerous'));
    }

    public static function getInstance() : SentinelAggressionLevels
    {
        if(!isset(self::$instance)) {
            self::$instance = new SentinelAggressionLevels();
        }

        return self::$instance;
    }

    private function registerLevel(string $id, string $label) : void
    {
        $this->levels[$id] = new SentinelAggressionLevel($id, $label);
    }

    /**
     * @return SentinelAggressionLevel[]
     */
    public function getAll() : array
    {
        return array_values($this->levels);
    }

    /**
     * @param string $id
     * @return SentinelAggressionLevel
     * @throws TrackerException
     */
    public function getByID(string $id) : SentinelAggressionLevel
    {
        if(isset($this->levels[$id])) {
            return $this->levels[$id];
        }

        throw new TrackerException(
            'Unknown sentinel aggression level.',
            sprintf(
                'Unknown aggression level ID [%s]. Know IDs are: [%s].',
                $id,
                implode(', ', $this->getIDs())
            )
        );
    }

    /**
     * @return string[]
     */
    public function getIDs() : array
    {
        return array_keys($this->levels);
    }
}
