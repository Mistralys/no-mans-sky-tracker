<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

class PlanetRating
{
    private string $id;
    private string $label;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function isUnrated() : bool
    {
        return $this->getID() === PlanetRatings::RATING_UNRATED;
    }

    public function getNumber() : int
    {
        if($this->isUnrated()) {
            return -1;
        }

        return (int)$this->getID();
    }

    public function getLabelForSelect() : string
    {
        if($this->isUnrated()) {
            return $this->getLabel();
        }

        return
            $this->getNumber().
            ' - '.
            $this->getLabel();
    }
}
