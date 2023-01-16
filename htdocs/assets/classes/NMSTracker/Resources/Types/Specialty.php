<?php

declare(strict_types=1);

namespace NMSTracker\Resources\Types;

use NMSTracker\Resources\BaseResourceType;
use NMSTracker\Resources\ResourceTypesCollection;

class Specialty extends BaseResourceType
{
    public function getID() : string
    {
        return ResourceTypesCollection::TYPE_SPECIALTY;
    }

    public function getLabel() : string
    {
        return t('Specialty');
    }
}
