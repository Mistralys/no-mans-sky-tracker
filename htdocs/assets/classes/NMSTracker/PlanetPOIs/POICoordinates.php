<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use AppUtils\NumberInfo;
use NMSTracker;
use function AppUtils\parseNumber;

class POICoordinates
{
    private NumberInfo $longitude;
    private NumberInfo $latitude;

    public function __construct(float $longitude, float $latitude)
    {
        $this->longitude = parseNumber($longitude);
        $this->latitude = parseNumber($latitude);
    }

    public function getLongitude() : NumberInfo
    {
        return $this->longitude;
    }

    public function getLatitude() : NumberInfo
    {
        return $this->latitude;
    }

     public function toList() : string
     {
         return (string)sb()->mono(sprintf(
             '%s %s %s',
             NMSTracker::icon()->move(),
             $this->formatNumber($this->getLongitude()->getValue()),
             $this->formatNumber($this->getLatitude()->getValue())
         ));
     }

     private function formatNumber(float $number) : string
     {
         $string = number_format($number, 2, '.', '');
         $parts = explode('.', $string);
         $length = 5;

         return str_replace(
             '#',
             '&#160;',
             sprintf(
                '%s.%s',
                str_pad($parts[0], $length, '#', STR_PAD_LEFT),
                $parts[1]
            )
         );
     }
}