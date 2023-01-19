<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations;

abstract class BaseStationOfferType
{
    abstract public function getID() : string;
    abstract public function getLabel() : string;
}