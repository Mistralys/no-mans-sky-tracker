<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;

class PlanetStatusScreen
    extends Application_Admin_Area_Mode_Submode_Action
    implements ViewPlanetScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;

    public const URL_NAME = 'status';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Status');
    }

    public function getTitle() : string
    {
        return t('Planet status');
    }

    public function getAbstract() : string
    {
        return '';
    }

    protected function _handleBreadcrumb() : void
    {
    }

    protected function _renderContent()
    {
        $planet = $this->getPlanet();
        $system = $planet->getSolarSystem();

        $grid = $this->ui->createPropertiesGrid();

        $grid->add(t('Solar system'), $system->getLabelLinked())
            ->setComment(sb()
                ->t('Star type:')->add($system->getStarType()->getLabel())
                ->add('|')
                ->t('Dominant race:')->add($system->getRace()->getLabel())
            );;
        $planet->injectProperties($grid);

        $grid->addHeader(t('Resources'));
        $grid->addMerged($this->renderResources());

        $grid->addHeader(t('Outposts'));
        $grid->addMerged($this->renderOutposts());

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }

    private function renderResources() : string
    {
        $resources = $this->getPlanet()->getResources();
        $items = array();

        foreach($resources as $resource)
        {
            $items[] = $resource->getLabel();
        }

        return (string)sb()->ul($items);
    }

    private function renderOutposts() : string
    {
        $outposts = $this->getPlanet()->getOutposts();
        $items = array();

        foreach ($outposts as $outpost)
        {
            $items[] = $outpost->getLabelLinked();
        }

        return (string)sb()->ul($items);
    }
}
