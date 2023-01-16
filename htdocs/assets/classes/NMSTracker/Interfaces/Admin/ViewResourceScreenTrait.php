<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Resources\ResourceRecord;

trait ViewResourceScreenTrait
{
    public function getResource() : ResourceRecord
    {
        $collection = ClassFactory::createResources();
        $resource = $collection->getByRequest();

        if($resource !== null) {
            return $resource;
        }

        $this->redirectTo($collection->getAdminListURL());
    }
}
