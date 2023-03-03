<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

class PlanetRatings
{
    public const ERROR_UNKNOWN_RATING_ID = 131101;

    public const RATING_UNRATED = 'unrated';
    public const RATING_0 = '0';
    public const RATING_1 = '1';
    public const RATING_2 = '2';
    public const RATING_3 = '3';
    public const RATING_4 = '4';
    public const RATING_5 = '5';
    public const DEFAULT_RATING = self::RATING_UNRATED;

    private static ?PlanetRatings $instance = null;
    /**
     * @var array<string,PlanetRating>
     */
    private array $ratings = array();

    public function __construct()
    {
        $this->registerRating(self::RATING_UNRATED, t('Unrated'));
        $this->registerRating(self::RATING_0, t('Horrid'));
        $this->registerRating(self::RATING_1, t('Banal'));
        $this->registerRating(self::RATING_2, t('Passable'));
        $this->registerRating(self::RATING_3, t('Interesting'));
        $this->registerRating(self::RATING_4, t('Memorable'));
        $this->registerRating(self::RATING_5, t('Galactic wonder'));
    }

    /**
     * @return PlanetRating[]
     */
    public function getAll() : array
    {
        return array_values($this->ratings);
    }

    /**
     * @param string $id
     * @return PlanetRating
     * @throws PlanetException {@see self::ERROR_UNKNOWN_RATING_ID}
     */
    public function getByID(string $id) : PlanetRating
    {
        if(isset($this->ratings[$id])) {
            return $this->ratings[$id];
        }

        throw new PlanetException(
            'Unknown planet rating ID.',
            sprintf(
                'The planet rating ID [%s] does not exist. Known IDs are: [%s].',
                $id,
                implode(', ', $this->getIDs())
            ),
            self::ERROR_UNKNOWN_RATING_ID
        );
    }

    public function idExists(string $id) : bool
    {
        return isset($this->ratings[$id]);
    }

    /**
     * @return string[]
     */
    public function getIDs() : array
    {
        return array_keys($this->ratings);
    }

    private function registerRating(string $id, string $label) : void
    {
        $this->ratings[$id] = new PlanetRating($id, $label);
    }

    public static function getInstance() : PlanetRatings
    {
        if(!isset(self::$instance)) {
            self::$instance = new PlanetRatings();
        }

        return self::$instance;
    }
}
