<?php

declare(strict_types=1);

namespace NMSTracker\Planets\Container;

use AppUtils\HTMLTag;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker\Planets\PlanetsContainer;
use UI_Renderable;

class PlanetsBulletRenderer extends UI_Renderable
{
    private PlanetsContainer $container;

    public function __construct(PlanetsContainer $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function _render() : string
    {
        $planets = $this->container->getAll();
        $items = array();

        foreach($planets as $planet)
        {
            $items[] = $this->renderPlanet($planet);
        }

        return (string)HTMLTag::create('ul')
            ->addClass('unstyled')
            ->setContent('<li>'.implode('</li><li>', $items).'</li>');

    }

    private function renderPlanet(PlanetRecord $planet) : string
    {
        return $planet->getLabelLinked();
    }
}
