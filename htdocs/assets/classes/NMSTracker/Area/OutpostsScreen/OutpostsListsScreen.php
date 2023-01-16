<?php

declare(strict_types=1);

namespace NMSTracker\Area\OutpostsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\OutpostsScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\OutpostsCollection;
use NMSTracker_User;

/**
 * @property OutpostFilterCriteria $filters
 * @property NMSTracker_User $user
 * @property OutpostsScreen $area
 */
class OutpostsListsScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'outposts-list';
    public const GRID_NAME = 'global-outposts-list';

    public const COL_LABEL = 'label';
    public const COL_SYSTEM = 'system';
    public const COL_PLANET = 'planet';
    public const COL_ROLE = 'role';
    public const COL_SENTINELS = 'sentinels';
    public const COL_RESOURCES = 'resources';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getGridName() : string
    {
        return self::GRID_NAME;
    }

    /**
     * @return OutpostsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createOutposts();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $outpost = ClassHelper::requireObjectInstanceOf(OutpostRecord::class, $record);
        $planet = $outpost->getPlanet();

        return array(
            self::COL_LABEL => $outpost->getLabelLinked(),
            self::COL_SYSTEM => $outpost->getSolarSystem()->getLabelLinked(),
            self::COL_PLANET => $planet->getLabelLinked(),
            self::COL_ROLE => $outpost->getRole()->getLabelLinked(),
            self::COL_SENTINELS => $planet->getSentinelLevel()->getLabelLinked(),
            self::COL_RESOURCES => $planet->getResourceFilters()->getContainer()->renderBulletList()
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SYSTEM, t('Solar system'));

        $this->grid->addColumn(self::COL_PLANET, t('Planet'));

        $this->grid->addColumn(self::COL_ROLE, t('Role'));

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));

        $this->grid->addColumn(self::COL_RESOURCES, t('Resources'));

        $this->grid->enableColumnControls(5);
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewOutposts();
    }

    public function getNavigationTitle() : string
    {
        return t('Outposts');
    }

    public function getTitle() : string
    {
        return t('Global outposts overview');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->outpost());
    }
}
