<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\ResourcesCollection;

/**
 * @method ResourceRecord[] getItemsObjects()
 */
class ResourceFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_INCLUDE_IDS = 'include_ids';

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            ResourcesCollection::PRIMARY_NAME,
            $this->getCriteriaValues(self::FILTER_INCLUDE_IDS)
        );
    }

    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
    }

    public function includeID(int $id) : self
    {
        return $this->selectCriteriaValue(
            self::FILTER_INCLUDE_IDS,
            $id
        );
    }

    /**
     * @param int[] $ids
     * @return $this
     */
    public function includeIDs(array $ids, bool $allowEmpty) : self
    {
        if(empty($ids) && !$allowEmpty) {
            $ids = array(PHP_INT_MIN);
        }

        foreach($ids as $id)
        {
            $this->includeID($id);
        }

        return $this;
    }

    public function getContainer() : ResourceContainer
    {
        return ResourceContainer::create($this);
    }
}
