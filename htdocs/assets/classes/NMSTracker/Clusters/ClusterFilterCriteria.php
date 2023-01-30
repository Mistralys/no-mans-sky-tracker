<?php

declare(strict_types=1);

namespace NMSTracker\Clusters;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;

/**
 * @method ClusterRecord[] getItemsObjects()
 */
class ClusterFilterCriteria extends DBHelper_BaseFilterCriteria
{
    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
    }
}
