<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\ClassFactory;
use NMSTracker\Tags\TagRecord;

trait ViewTagScreenTrait
{
    public function getTag() : TagRecord
    {
        $collection = ClassFactory::createTags();
        $tag = $collection
            ->getByRequest();

        if($tag !== null) {
            return $tag;
        }

        $this->redirectTo($collection->getAdminListURL());
    }
}
