<?php

declare(strict_types=1);

namespace NMSTracker\Tags;

use Application_Exception;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker\PlanetsCollection;
use NMSTracker\TagsCollection;

/**
 * @method TagRecord[] getItemsObjects()
 */
class TagFilterCriteria extends DBHelper_BaseFilterCriteria
{
    protected function _registerJoins() : void
    {
        $this->registerJoinPlanetTags();
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
        $container
            ->table('{table_tags}', TagsCollection::TABLE_NAME)
            ->table('{table_planet_tags}', PlanetsCollection::TABLE_TAGS)

            ->field('{tag_primary}', TagsCollection::PRIMARY_NAME)
            ->field('{planet_primary}', PlanetsCollection::PRIMARY_NAME);
    }

    protected function prepareQuery() : void
    {
        $this->makeDistinct();

        $this->applyFilterPlanetTags();

        $this->addGroupByStatement('{table_tags}.{tag_primary}');
    }

    protected function getCountColumn() : string
    {
        return $this->getColID();
    }

    public function getColID() : string
    {
        return (string)$this->statement('{table_tags}.{tag_primary}');
    }

    public function getContainer() : TagContainer
    {
        return TagContainer::create($this);
    }

    // region: Filter by planet

    public const FILTER_PLANETS = 'planets';
    public const JOIN_PLANET_TAGS = 'join_planet_tags';

    /**
     * @param PlanetRecord $planet
     * @return $this
     * @throws Application_Exception
     */
    public function selectPlanet(PlanetRecord $planet) : self
    {
        return $this->selectCriteriaValue(self::FILTER_PLANETS, $planet->getID());
    }

    private function applyFilterPlanetTags() : void
    {
        if(!$this->hasCriteriaValues(self::FILTER_PLANETS))
        {
            return;
        }

        $this->requireJoin(self::JOIN_PLANET_TAGS);

        $this->addWhereColumnIN(
            $this->statement('{table_planet_tags}.{planet_primary}'),
            $this->getCriteriaValues(self::FILTER_PLANETS)
        );
    }

    private function registerJoinPlanetTags() : void
    {
        $this->registerJoin(
            self::JOIN_PLANET_TAGS,
            $this->statement("
                LEFT JOIN
                    {table_planet_tags}
                ON
                    {table_planet_tags}.{tag_primary}={table_tags}.{tag_primary}
             ")
        );
    }

    // endregion
}
