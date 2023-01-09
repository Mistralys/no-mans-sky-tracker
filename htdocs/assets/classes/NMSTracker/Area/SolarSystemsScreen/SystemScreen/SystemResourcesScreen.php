<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\SolarSystems\SystemResourceResult;
use NMSTracker\SolarSystemsCollection;
use UI_DataGrid;

/**
 * @property ResourceFilterCriteria $filters
 */
class SystemResourcesScreen
    extends Application_Admin_Area_Mode_Submode
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'system-resources';
    public const COL_LABEL = 'label';
    public const COL_PLANETS = 'planets';

    private UI_DataGrid $grid;

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function _handleActions() : bool
    {
        if(parent::_handleActions() === false) {
            return false;
        }

        $this->createDataGrid();

        return true;
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setSubtitle($this->getTitle())
            ->setAbstract(t('These are all resources available on the planets in the system.'));
    }

    protected function _renderContent()
    {
        return $this->renderer
            ->appendDataGrid($this->grid, $this->compileEntries())
            ->makeWithoutSidebar();
    }

    private function compileEntries() : array
    {
        $entries = array();
        $results = $this->getSolarSystem()->getResources()->getResults();

        foreach($results as $result)
        {
            $entries[] = $this->getEntryData($result);
        }

        return $entries;
    }

    protected function getEntryData(SystemResourceResult $record) : array
    {
        return array(
            self::COL_LABEL => $record->getResource()->getLabel(),
            self::COL_PLANETS => $this->renderPlanets($record)
        );
    }

    protected function createDataGrid() : void
    {
        $this->grid = $this->getUI()->createDataGrid('system_resources');

        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortingString();

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'));

        $this->grid->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, (string)$this->getSolarSystem()->getID());
    }

    public function getNavigationTitle() : string
    {
        return t('Resources');
    }

    public function getTitle() : string
    {
        return t('System resources overview');
    }

    private function renderPlanets(SystemResourceResult $record) : string
    {
        $planets = $record->getPlanets();

        foreach($planets as $planet)
        {
            $items[] = $planet->getLabelLinked();
        }

        return implode(', ', $items);
    }

    public function getDefaultAction() : string
    {
        return '';
    }
}
