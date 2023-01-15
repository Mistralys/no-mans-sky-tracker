<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;
use UI;

/**
 * @property PlanetFilterCriteria $filters
 * @property SystemScreen $mode
 */
class PlanetListScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'list';
    public const COL_LABEL = 'label';
    public const COL_TYPE = 'type';
    public const COL_SENTINELS = 'sentinels';
    public const COL_SCAN_COMPLETE = 'scan_complete';
    public const COL_OUTPOSTS = 'outposts';
    public const COL_RESOURCES = 'resources';

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

    /**
     * @return PlanetsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setSubtitle($this->getTitle());
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_planet', t('Add planet'))
            ->setIcon(UI::icon()->add())
            ->link($this->getSolarSystem()->getAdminCreatePlanetURL());

        parent::_handleSidebar();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof PlanetRecord)
        {
            return array(
                self::COL_LABEL => sb()->add($record->getLabelLinked())->add($record->getMoonIcon()),
                self::COL_TYPE => $record->getType()->getLabelLinked(),
                self::COL_SENTINELS => $record->getSentinelLevel()->getLabelLinked(),
                self::COL_SCAN_COMPLETE => UI::prettyBool($record->isScanComplete())->makeYesNo(),
                self::COL_OUTPOSTS => sb()->link(
                    (string)$record->countOutposts(),
                    $record->getAdminOutpostsURL()
                ),
                self::COL_RESOURCES => $record->getResourceFilters()->countItems()
            );
        }

        return array();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectSolarSystem($this->getSolarSystem());
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_TYPE, t('Type'));

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));

        $this->grid->addColumn(self::COL_SCAN_COMPLETE, t('Scan complete?'))
            ->alignCenter();

        $this->grid->addColumn(self::COL_OUTPOSTS, t('Outposts'))
            ->alignRight();

        $this->grid->addColumn(self::COL_RESOURCES, t('Resources'))
            ->alignRight();

        $this->grid->enableLimitOptionsDefault();
        $this->grid->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, $this->getSolarSystem()->getID());
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminViewURL();
    }
}
