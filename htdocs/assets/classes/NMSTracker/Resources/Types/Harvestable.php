<?php

declare(strict_types=1);

namespace NMSTracker\Resources\Types;

use NMSTracker\Resources\BaseResourceType;
use NMSTracker\Resources\ResourceTypesCollection;

class Harvestable extends BaseResourceType
{
    public function getID() : string
    {
        return ResourceTypesCollection::TYPE_HARVESTABLE;
    }

    public function getLabel() : string
    {
        return t('Harvestable');
    }
}
