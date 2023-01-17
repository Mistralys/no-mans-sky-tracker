<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use AppUtils\ClassHelper;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetPOIs\PlanetPOIFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use UI_DataGrid_Action;

/**
 * @property PlanetPOIFilterCriteria $filters
 */
class PlanetPOIsScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements ViewPlanetScreenInterface
{
    use ViewPlanetScreenTrait;
    use ViewSystemScreenTrait;

    public const URL_NAME = 'planet-pois';
    public const COL_LABEL = 'label';
    public const COL_COORDS = 'coords';
    public const COL_PRIMARY = 'primary';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return PlanetPOIsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectPlanet($this->getPlanet());
    }

    public function getAbstract() : string
    {
        return '';
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_poi', t('Add a POI...'))
            ->setTooltip(t('Displays the form to add a point of interest on the planet.'))
            ->setIcon(NMSTracker::icon()->add())
            ->makeLinked($this->getPlanet()->getAdminCreatePOIURL());

        parent::_handleSidebar();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $poi = ClassHelper::requireObjectInstanceOf(
            PlanetPOIRecord::class,
            $record
        );

        return array(
            self::COL_PRIMARY => $poi->getID(),
            self::COL_LABEL => $poi->getLabelLinked(),
            self::COL_COORDS => $poi->getCoords()->toList()
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_COORDS, t('Coordinates'));
    }

    protected function configureActions() : void
    {
        $this->grid->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, $this->getSolarSystem()->getID());
        $this->grid->addHiddenVar(PlanetsCollection::PRIMARY_NAME, $this->getPlanet()->getID());

        $this->grid->enableMultiSelect(self::COL_PRIMARY);

        $this->grid->addAction('delete', t('Delete...'))
            ->makeDangerous()
            ->makeConfirm(sb()
                ->para(t('This will delete the selected POIs.'))
                ->para(sb()->cannotBeUndone())
            )
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'multiDelete')),
                array($this, 'multiDelete')
            ));
    }

    private function multiDelete(UI_DataGrid_Action $action) : void
    {
        $action->createRedirectMessage($this->getPlanet()->getAdminPOIsURL())
            ->none(t('No points of interest selected to delete.'))
            ->single(t('The point of interest %1$s has been deleted at %2$s.', '$label', '$time'))
            ->multiple(t('%1$s points of interest have been deleted at %2$s.', '$amount', '$time'))
            ->processDeleteDBRecords($this->createCollection())
            ->redirect();
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminStatusURL();
    }

    public function getNavigationTitle() : string
    {
        return t('POIs');
    }

    public function getTitle() : string
    {
        return t('Points of interest');
    }
}
