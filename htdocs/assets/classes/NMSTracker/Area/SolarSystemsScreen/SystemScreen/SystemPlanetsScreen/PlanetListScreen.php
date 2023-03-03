<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use NMSTracker\Interfaces\Admin\PlanetListScreenInterface;
use NMSTracker\Interfaces\Admin\PlanetListScreenTrait;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\SolarSystemsCollection;
use UI;

/**
 * @property PlanetFilterCriteria $filters
 * @property SystemScreen $mode
 */
class PlanetListScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements
    ViewSystemScreenInterface,
    PlanetListScreenInterface
{
    use ViewSystemScreenTrait;
    use PlanetListScreenTrait;

    public const URL_NAME = 'list';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Planets');
    }

    public function getTitle() : string
    {
        return t('System planets overview');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setSubtitle($this->getTitle());
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return $this->filters;
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_planet', t('Add planet'))
            ->setIcon(UI::icon()->add())
            ->link($this->getSolarSystem()->getAdminCreatePlanetURL());

        parent::_handleSidebar();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectSolarSystem($this->getSolarSystem());
    }

    protected function configureActions() : void
    {
    }

    protected function handleColumnVisibility() : void
    {
        $this->disableColumn(PlanetListScreenInterface::COL_SYSTEM);
    }

    public function getPersistVars() : array
    {
        return array(
            SolarSystemsCollection::PRIMARY_NAME => $this->getSolarSystem()->getID()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminViewURL();
    }
}
