<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations;

use AppUtils\ClassHelper;
use NMSTracker\SpaceStations\OfferTypes\StationBuyOffer;
use NMSTracker\SpaceStations\OfferTypes\StationSellOffer;
use NMSTracker\TrackerException;

class StationOfferTypesCollection
{
    public const ERROR_UNKNOWN_OFFER_TYPE = 124401;

    public const OFFER_TYPE_BUY = 'buying';
    public const OFFER_TYPE_SELL = 'selling';

    /**
     * @var BaseStationOfferType[]
     */
    private array $types;

    public function __construct()
    {
        $this->types = array(
            new StationBuyOffer(),
            new StationSellOffer()
        );
    }

    public function getAll() : array
    {
        return $this->types;
    }

    public function getBuyOfferType() : StationBuyOffer
    {
        return ClassHelper::requireObjectInstanceOf(
            StationBuyOffer::class,
            $this->getByID(self::OFFER_TYPE_BUY)
        );
    }

    public function getSellOfferType() : StationSellOffer
    {
        return ClassHelper::requireObjectInstanceOf(
            StationSellOffer::class,
            $this->getByID(self::OFFER_TYPE_SELL)
        );
    }

    public function idExists(string $id) : bool
    {
        foreach($this->types as $type)
        {
            if($type->getID() === $id) {
                return true;
            }
        }

        return false;
    }

    public function getByID(string $id) : BaseStationOfferType
    {
        foreach($this->types as $type)
        {
            if($type->getID() === $id) {
                return $type;
            }
        }

        throw new TrackerException(
            'Unknown station offer type.',
            sprintf(
                'Offer type [%s] does not exist. Known IDs: [%s].',
                $id,
                implode(', ', $this->getIDs())
            ),
            self::ERROR_UNKNOWN_OFFER_TYPE
        );
    }

    /**
     * @return string[]
     */
    public function getIDs() : array
    {
        $result = array();

        foreach ($this->types as $type)
        {
            $result[] = $type->getID();
        }

        return $result;
    }
}
