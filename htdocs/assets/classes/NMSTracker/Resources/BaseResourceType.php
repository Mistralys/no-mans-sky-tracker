<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

abstract class BaseResourceType
{
    abstract public function getID() : string;

    abstract public function getLabel() : string;
}
