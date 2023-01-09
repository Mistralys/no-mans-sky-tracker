<?php

declare(strict_types=1);

namespace NMSTracker\Races;

use DBHelper_BaseFilterCriteria;
use DBHelper_StatementBuilder_ValuesContainer;

class RaceFilterCriteria extends DBHelper_BaseFilterCriteria
{
    protected function _registerJoins() : void
    {
    }

    protected function _registerStatementValues(DBHelper_StatementBuilder_ValuesContainer $container) : void
    {
    }
}
