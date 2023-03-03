<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use Application\Interfaces\Admin\CollectionListInterface;
use NMSTracker\Planets\PlanetFilterCriteria;

interface PlanetListScreenInterface extends CollectionListInterface
{
    public const COL_RESOURCES = 'resources';
    public const COL_OUTPOSTS = 'outposts';
    public const COL_TYPE = 'type';
    public const COL_SYSTEM = 'system';
    public const COL_SCAN_COMPLETE = 'scan_complete';
    public const COL_PLANETFALL = 'planetfall';
    public const COL_SENTINELS = 'sentinels';
    public const COL_FAUNA = 'fauna';
    public const COL_LABEL = 'label';

    public function getPlanetFilters() : PlanetFilterCriteria;
}
