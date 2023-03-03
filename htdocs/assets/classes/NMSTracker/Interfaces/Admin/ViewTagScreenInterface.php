<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use Application_Admin_ScreenInterface;
use NMSTracker\Tags\TagRecord;

interface ViewTagScreenInterface extends Application_Admin_ScreenInterface
{
    public function getTag() : TagRecord;
}
