<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use Application_Admin_ScreenInterface;
use NMSTracker\Resources\ResourceRecord;

interface ViewResourceScreenInterface extends Application_Admin_ScreenInterface
{
    public function getResource() : ResourceRecord;
}
