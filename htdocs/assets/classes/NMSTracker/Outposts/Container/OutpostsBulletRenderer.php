<?php

declare(strict_types=1);

namespace NMSTracker\Outposts\Container;

use AppUtils\HTMLTag;
use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Outposts\OutpostsContainer;
use UI_Renderable;

class OutpostsBulletRenderer extends UI_Renderable
{
    private OutpostsContainer $container;
    private bool $appendPlanetName = false;

    public function __construct(OutpostsContainer $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    public function makeWithPlanetName(bool $withName=true) : self
    {
        $this->appendPlanetName = $withName;
        return $this;
    }

    protected function _render() : string
    {
        $outposts = $this->container->getAll();

        if(empty($outposts)) {
            return $this->renderEmpty();
        }

        $items = array();

        foreach($outposts as $outpost)
        {
            $items[] = $this->renderOutpost($outpost);
        }

        return (string)HTMLTag::create('ul')
            ->addClass('unstyled')
            ->setContent('<li>'.implode('</li><li>', $items).'</li>');
    }

    private function renderEmpty() : string
    {
        return (string)$this
            ->getUI()
            ->createMessage(t('No outposts present.'))
            ->makeNotDismissable()
            ->makeSlimLayout()
            ->makeInfo()
            ->enableIcon();
    }

    private function renderOutpost(OutpostRecord $outpost) : string
    {
        $label = sb()->add($outpost->getLabelLinked());

        if($this->appendPlanetName)
        {
            $label->muted(t(
                'on %1$s',
                $outpost->getPlanet()->getLabelLinked()
            ));
        }

        return (string)$label;
    }
}
