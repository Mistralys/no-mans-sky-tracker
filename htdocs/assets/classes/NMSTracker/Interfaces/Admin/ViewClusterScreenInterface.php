<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces;

use Application_Admin_ScreenInterface;
use NMSTracker\Clusters\ClusterRecord;

interface ViewClusterScreenInterface extends Application_Admin_ScreenInterface
{
    public function getCluster() : ClusterRecord;
}
