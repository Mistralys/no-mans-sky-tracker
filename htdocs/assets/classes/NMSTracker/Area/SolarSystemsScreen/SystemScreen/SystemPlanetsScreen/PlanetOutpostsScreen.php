<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use classes\NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\PlanetsCollection;
use NMSTracker_User;

/**
 * @property OutpostFilterCriteria $filters
 */
class PlanetOutpostsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements ViewPlanetScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;

    public const URL_NAME = 'outposts';
    public const COL_LABEL = 'label';
    public const COL_ROLE = 'role';
    public const COL_SERVICES = 'services';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Outposts');
    }

    public function getTitle() : string
    {
        return t('Available outposts');
    }

    public function getAbstract() : string
    {
        return '';
    }

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createOutposts();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectPlanet($this->getPlanet());
        $this->filters->withServiceCounts();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof OutpostRecord)
        {
            return array(
                self::COL_LABEL => sb()->linkRight(
                    $record->getLabel(),
                    $record->getAdminStatusURL(),
                    NMSTracker_User::RIGHT_VIEW_OUTPOSTS
                ),
                self::COL_ROLE => $record->getRole()->getLabel(),
                self::COL_SERVICES => $entry->getColumnInt($this->filters->getColServicesCount()->getName())
            );
        }

        return array();
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_outpost', t('Add an outpost'))
            ->setIcon(NMSTracker::icon()->add())
            ->makeLinked($this->getPlanet()->getAdminCreateOutpostURL());

        parent::_handleSidebar();
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_ROLE, t('Role'));

        $this->grid->addColumn(self::COL_SERVICES, t('Services'))
            ->setSortable(false, $this->filters->getColServicesCount()->getName());
    }

    protected function configureActions() : void
    {
        $this->grid->enableLimitOptionsDefault();
        $this->grid->addHiddenVar(PlanetsCollection::PRIMARY_NAME, (string)$this->getPlanet()->getID());
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getPlanet()->getAdminOutpostsURL();
    }
}