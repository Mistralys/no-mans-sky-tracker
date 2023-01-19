<?php

declare(strict_types=1);

namespace NMSTracker\Resources\Types;

use NMSTracker\Resources\BaseResourceType;
use NMSTracker\Resources\ResourceTypesCollection;

class Tradeable extends BaseResourceType
{
    public function getID() : string
    {
        return ResourceTypesCollection::TYPE_TRADEABLE;
    }

    public function getLabel() : string
    {
        return t('Trade commodity');
    }
}
