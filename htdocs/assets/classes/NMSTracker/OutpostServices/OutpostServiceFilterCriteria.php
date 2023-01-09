<?php

declare(strict_types=1);

namespace NMSTracker\OutpostServices;

use Application_Exception;
use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;
use NMSTracker\OutpostServicesCollection;

/**
 * @method OutpostServiceRecord[] getItemsObjects()
 */
class OutpostServiceFilterCriteria extends DBHelper_BaseFilterCriteria
{
    public const FILTER_INCLUDE_IDS = 'include_ids';

    public function getContainer() : OutpostServiceContainer
    {
        return OutpostServiceContainer::create($this);
    }

    /**
     * @param int[] $ids
     * @return $this
     * @throws Application_Exception
     */
    public function includeIDs(array $ids, bool $allowEmpty) : self
    {
        if(empty($ids) && !$allowEmpty)
        {
            $ids = array(PHP_INT_MIN);
        }

        foreach ($ids as $id)
        {
            $this->includeID($id);
        }

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     * @throws Application_Exception
     */
    public function includeID(int $id) : self
    {
        return $this->selectCriteriaValue(self::FILTER_INCLUDE_IDS, $id);
    }

    protected function prepareQuery() : void
    {
        $this->addWhereColumnIN(
            OutpostServicesCollection::PRIMARY_NAME,
            $this->getCriteriaValues(self::FILTER_INCLUDE_IDS)
        );
    }

    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
    }
}
