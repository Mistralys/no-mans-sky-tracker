<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen;

use Application_Admin_Area_Mode;
use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetTypes\PlanetTypeFilterCriteria;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\PlanetTypesCollection;
use NMSTracker_User;

/**
 * @property PlanetTypeFilterCriteria $filters
 * @property NMSTracker_User $user
 */
class PlanetTypesListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';

    public const COL_LABEL = 'label';
    public const COL_PLANETS = 'planets';

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPlanetTypes();
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('List');
    }

    public function getTitle() : string
    {
        return t('Planet types overview');
    }

    /**
     * @return PlanetTypesCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanetTypes();
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add-planet-type', t('Add planet type...'))
            ->setIcon(NMSTracker::icon()->add())
            ->link($this->createCollection()->getAdminCreateURL());

        parent::_handleSidebar();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $type = ClassHelper::requireObjectInstanceOf(
            PlanetTypeRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $type->getLabelLinked(),
            self::COL_PLANETS => sb()->link(
                (string)$entry->getColumnInt($this->filters->getColPlanetCount()->getName()),
                $type->getAdminPlanetsURL()
            )
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, PlanetTypesCollection::COL_LABEL);

        $this->grid->addColumn(self::COL_PLANETS, t('Planets'))
            ->setSortable(false, $this->filters->getColPlanetCount()->getSecondarySelectValue());

        $this->filters->withPlanetCounts();
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon($this->createCollection()->getIcon());
    }
}
