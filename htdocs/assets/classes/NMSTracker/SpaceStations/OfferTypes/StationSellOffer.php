<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations\OfferTypes;

use NMSTracker\SpaceStations\BaseStationOfferType;
use NMSTracker\SpaceStations\StationOfferTypesCollection;

class StationSellOffer extends BaseStationOfferType
{
    public function getID() : string
    {
        return StationOfferTypesCollection::OFFER_TYPE_SELL;
    }

    public function getLabel() : string
    {
        return t('Sell offer');
    }
}