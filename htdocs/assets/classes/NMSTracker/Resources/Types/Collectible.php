<?php

declare(strict_types=1);

namespace NMSTracker\Resources\Types;

use NMSTracker\Resources\BaseResourceType;
use NMSTracker\Resources\ResourceTypesCollection;

class Collectible extends BaseResourceType
{
    public function getID() : string
    {
        return ResourceTypesCollection::TYPE_COLLECTIBLE;
    }

    public function getLabel() : string
    {
        return t('Collectible');
    }
}
