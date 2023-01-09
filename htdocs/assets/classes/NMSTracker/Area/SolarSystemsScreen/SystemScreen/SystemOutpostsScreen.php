<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use classes\NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\OutpostsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker_User;

/**
 * @property OutpostFilterCriteria $filters
 */
class SystemOutpostsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'system-outposts';
    public const COL_LABEL = 'label';
    public const COL_PLANET = 'planet';
    public const COL_ROLE = 'role';
    public const COL_SERVICES = 'services';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createOutposts();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectSolarSystem($this->getSolarSystem());
        $this->filters->withServiceCounts();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof OutpostRecord)
        {
            return array(
                self::COL_LABEL => $record->getLabelLinked(),
                self::COL_ROLE => $record->getRole()->getLabelLinked(),
                self::COL_PLANET => $record->getPlanet()->getLabelLinked(),
                self::COL_SERVICES => $entry->getColumnInt($this->filters->getColServicesCount()->getName())
            );
        }

        return array();
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setTitle($this->getSolarSystem()->getLabel())
            ->setSubtitle($this->getTitle());
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_ROLE, t('Role'));

        $this->grid->addColumn(self::COL_SERVICES, t('Services'))
            ->setSortable(false, $this->filters->getColServicesCount()->getName());

        $this->grid->addColumn(self::COL_PLANET, t('Planet'));
    }

    protected function configureActions() : void
    {
        $this->grid->enableLimitOptionsDefault();
        $this->grid->addHiddenVar(PlanetsCollection::PRIMARY_NAME, (string)$this->getSolarSystem()->getID());
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminOutpostsURL();
    }

    public function getNavigationTitle() : string
    {
        return t('Outposts');
    }

    public function getTitle() : string
    {
        return t('System outposts overview');
    }
}
