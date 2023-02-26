<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\SolarSystems\SolarSystemFilterCriteria;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;
use UI;
use UI_DataGrid_Action_Confirm;

/**
 * @property SolarSystemFilterCriteria $filters
 */
class SystemsListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';
    public const COL_NAME = 'name';
    public const COL_PLANETS = 'planets';
    public const COL_ORBITAL_BODIES = 'bodies';
    public const COL_STAR = 'star';
    public const COL_RACE = 'race';
    public const COL_PRIMARY = 'primary';
    public const COL_OWN_DISCOVERY = 'own_discovery';
    public const COL_CORE_DISTANCE = 'core_distance';
    public const COL_HOSPITALITY = 'hospitality';

    public function getDefaultSubmode() : string
    {
        return '';
    }

    public function isUserAllowed() : bool
    {
        return true;
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Solar systems');
    }

    public function getTitle() : string
    {
        return t('Available solar systems');
    }

    /**
     * @return SolarSystemsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createSolarSystems();
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_system', t('Add a system'))
            ->setIcon(UI::icon()->add())
            ->makeLinked($this->createCollection()->getAdminCreateURL());

        parent::_handleSidebar();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof SolarSystemRecord)
        {
            return array(
                self::COL_PRIMARY => $record->getID(),
                self::COL_NAME => sb()
                    ->icon($record->getStarType()->getIcon())
                    ->add($record->getLabelLinked()
                ),
                self::COL_RACE => $record->getRace()->getLabelLinked(),
                self::COL_ORBITAL_BODIES => $record->getAmountPlanets(),
                self::COL_OWN_DISCOVERY => $record->getOwnershipBadge(),
                self::COL_HOSPITALITY => $record->getHospitalityPretty(),
                self::COL_PLANETS => sb()->linkRight(
                    (string)$entry->getColumnInt($this->filters->getColPlanetCount()->getName()),
                    $record->getAdminPlanetsURL(),
                    NMSTracker_User::RIGHT_VIEW_SOLAR_SYSTEMS
                ),
                self::COL_CORE_DISTANCE => $record->getCoreDistancePretty()
            );
        }

        return array();
    }

    protected function configureFilters() : void
    {
        $this->filters->withPlanetCounts();
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_NAME, t('Name'))
            ->setSortable(true, SolarSystemsCollection::COL_LABEL);

        $this->grid->addColumn(self::COL_RACE, t('Dominant race'));

        $this->grid->addColumn(self::COL_OWN_DISCOVERY, t('Discovery'))
            ->alignCenter();

        $this->grid->addColumn(self::COL_HOSPITALITY, t('Hospitality'))
            ->alignRight();

        $this->grid->addColumn(self::COL_ORBITAL_BODIES, t('Orbital bodies'))
            ->setSortable(false, SolarSystemsCollection::COL_AMOUNT_PLANETS)
            ->alignRight();

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'))
            ->alignRight()
            ->setSortable(false, $this->filters->getColPlanetCount()->getName());

        $this->grid->addColumn(self::COL_CORE_DISTANCE, t('Core distance'))
            ->setSortable(false, $this->filters->getColDistanceToCore());
    }

    protected function configureActions() : void
    {
        $this->grid->enableLimitOptionsDefault();
        $this->grid->enableMultiSelect(self::COL_PRIMARY);
        $this->grid->enableColumnControls();

        $this->grid->addConfirmAction(
            'delete',
            t('Delete...'),
            sb()
                ->para(t('This will delete the solar system, including all planets and outposts.'))
                ->para(sb()->cannotBeUndone())
        )
            ->setIcon(UI::icon()->delete())
            ->makeDangerous()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'multiDelete')),
                array($this, 'multiDelete')
            ));
    }

    private function multiDelete(UI_DataGrid_Action_Confirm $action) : void
    {
        $collection = $this->createCollection();

        $action->createRedirectMessage($collection->getAdminListURL())
            ->single(t('The solar system %1$s has been deleted successfully at %2$s.', '$label', '$time'))
            ->none(t('No solar system selected.'))
            ->multiple(t('%1$s solar systems have been deleted successfully at %2$s.', '$amount', '$time'))
            ->processDeleteDBRecords($collection)
            ->redirect();
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }
}
