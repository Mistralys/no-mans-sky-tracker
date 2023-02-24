<?php

declare(strict_types=1);

namespace NMSTracker\SentinelLevels;

use NMSTracker;
use UI;
use UI_Label;

class SentinelAggressionLevel
{
    public const AGGRESSION_PERCENTAGES = array(
        SentinelAggressionLevels::LEVEL_NONE => 0,
        SentinelAggressionLevels::LEVEL_GRAY => 20,
        SentinelAggressionLevels::LEVEL_ORANGE => 40,
        SentinelAggressionLevels::LEVEL_RED => 100
    );

    private string $id;
    private string $label;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function isHarmless() : bool
    {
        return $this->getID() === SentinelAggressionLevels::LEVEL_GRAY;
    }

    public function isAnnoying() : bool
    {
        return $this->getID() === SentinelAggressionLevels::LEVEL_ORANGE;
    }

    public function isDangerous() : bool
    {
        return $this->getID() === SentinelAggressionLevels::LEVEL_RED;
    }

    public function getAsPercentage() : int
    {
        return self::AGGRESSION_PERCENTAGES[$this->getID()];
    }

    public function getBadge() : UI_Label
    {
        $label = UI::label('')
            ->setIcon(NMSTracker::icon()->sentinels())
            ->setTooltip(sb()
                ->t('Sentinel aggression level:')
                ->add($this->getLabel())
            );

        if($this->isHarmless()) {
            $label->makeInfo();
        } else if($this->isAnnoying()) {
            $label->makeWarning();
        } else if($this->isDangerous()) {
            $label->makeDangerous();
        } else {
            $label->makeSuccess();
        }

        return $label;
    }
}
