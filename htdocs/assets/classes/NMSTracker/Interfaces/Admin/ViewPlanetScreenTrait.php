<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\OutpostSettingsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\OutpostStatusScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetAddOutpostScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetOutpostsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetPOIsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetSettingsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use UI;

/**
 * @see ViewPlanetScreenInterface
 */
trait ViewPlanetScreenTrait
{
    public function getPlanet() : PlanetRecord
    {
        $planet = ClassFactory::createPlanets()->getByRequest();

        if($planet !== null) {
            return $planet;
        }

        $this->redirectWithErrorMessage(
            t('No such planet found.'),
            $this->getSolarSystem()->getAdminPlanetsURL()
        );
    }

    protected function _handleTabs() : void
    {
        $planet = $this->getPlanet();

        // Outpost tab navigation when an outpost is
        // selected, which replaces the planet's tabs.
        $outpost = ClassFactory::createOutposts()->getByRequest();
        if($outpost !== null)
        {
            $this->_handleOutpostTabs($outpost);
        }
        else
        {
            $this->_handlePlanetTabs($planet);
        }

        $this->tabs->selectByAction();
    }

    private function _handlePlanetTabs(PlanetRecord $planet) : void
    {
        $this->tabs->appendTab(t('Overview'), PlanetStatusScreen::URL_NAME)
            ->makeLinked($planet->getAdminStatusURL())
            ->setIcon(NMSTracker::icon()->overview());

        $this->tabs->appendTab(t('Outposts'), PlanetOutpostsScreen::URL_NAME)
            ->makeLinked($planet->getAdminOutpostsURL())
            ->setIcon(NMSTracker::icon()->outpost());

        $this->tabs->appendTab(t('POIs'), PlanetPOIsScreen::URL_NAME)
            ->makeLinked($planet->getAdminPOIsURL())
            ->setIcon(NMSTracker::icon()->pointsOfInterest());

        $this->tabs->appendTab(t('Settings'), PlanetSettingsScreen::URL_NAME)
            ->makeLinked($planet->getAdminSettingsURL())
            ->setIcon(UI::icon()->settings());

        if($this instanceof PlanetAddOutpostScreen)
        {
            $this->tabs->selectTab($this->tabs->getTabByName(PlanetOutpostsScreen::URL_NAME));
        }
    }

    private function _handleOutpostTabs(OutpostRecord $outpost) : void
    {
        $this->tabs->appendTab(t('Overview'), OutpostStatusScreen::URL_NAME)
            ->makeLinked($outpost->getAdminStatusURL())
            ->setIcon(NMSTracker::icon()->overview());

        $this->tabs->appendTab(t('Settings'), OutpostSettingsScreen::URL_NAME)
            ->makeLinked($outpost->getAdminSettingsURL())
            ->setIcon(NMSTracker::icon()->settings());
    }

    protected function _handleBreadcrumb() : void
    {
        $planet = $this->getPlanet();

        $this->breadcrumb->appendItem(t('Planets'))
            ->makeLinked($this->getSolarSystem()->getAdminPlanetsURL());

        $this->breadcrumb->appendItem($this->getPlanet()->getLabel())
            ->makeLinked($planet->getAdminURL());

        $outpost = ClassFactory::createOutposts()->getByRequest();
        if($outpost !== null)
        {
            $this->breadcrumb->appendItem(t('Outposts'))
                ->makeLinked($planet->getAdminOutpostsURL());

            $this->breadcrumb->appendItem($outpost->getLabel())
                ->makeLinked($outpost->getAdminStatusURL());
        }

        $this->breadcrumb->appendItem($this->getNavigationTitle())
            ->makeLinked($planet->getAdminStatusURL());
    }

    protected function _handleHelp() : void
    {
        $subtitle = sb();
        $planet = $this->getPlanet();

        $outpost = ClassFactory::createOutposts()->getByRequest();
        if($outpost !== null)
        {
            $subtitle
                ->icon($outpost->getIcon())
                ->add($outpost->getLabel())
                ->muted(t('on %1$s', $planet->getLabelLinked()));

            $this->renderer->getSubtitle()
                ->addContextElement(
                    UI::button(t('Back'))
                        ->setIcon(UI::icon()->back())
                        ->makeMini()
                        ->link($planet->getAdminOutpostsURL())
                );
        }
        else
        {
            $subtitle->add($planet->getLabel());
            $this->renderer
                ->getSubtitle()
                ->setIcon(NMSTracker::icon()->planet())
                ->addBadge($planet->getOwnershipBadge());
        }

        $this->renderer
            ->setSubtitle($subtitle)
            ->setAbstract($this->getAbstract());
    }

    protected function _handleHiddenVars() : void
    {
        $planet = $this->getPlanet();

        $this->addHiddenVar(PlanetsCollection::PRIMARY_NAME, (string)$planet->getID());
        $this->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, (string)$planet->getSolarSystem()->getID());
    }
}
