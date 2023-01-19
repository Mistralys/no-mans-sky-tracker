<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations\OfferTypes;

use NMSTracker\SpaceStations\BaseStationOfferType;
use NMSTracker\SpaceStations\StationOfferTypesCollection;

class StationBuyOffer extends BaseStationOfferType
{
    public function getID() : string
    {
        return StationOfferTypesCollection::OFFER_TYPE_BUY;
    }

    public function getLabel() : string
    {
        return t('Buy offer');
    }
}