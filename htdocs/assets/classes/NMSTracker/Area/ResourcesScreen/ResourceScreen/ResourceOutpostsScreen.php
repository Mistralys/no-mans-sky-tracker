<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen\ResourceScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use AppUtils\ClassHelper;
use NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewResourceScreenInterface;
use NMSTracker\Interfaces\Admin\ViewResourceScreenTrait;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\Outposts\OutpostFilterSettings;
use NMSTracker\OutpostsCollection;
use NMSTracker\ResourcesCollection;
use NMSTracker_User;

/**
 * @property OutpostFilterCriteria $filters
 * @property OutpostFilterSettings $filterSettings
 * @property NMSTracker_User $user
 */
class ResourceOutpostsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewResourceScreenInterface
{
    use ViewResourceScreenTrait;

    public const URL_NAME = 'outposts';

    public const COL_LABEL = 'label';
    public const COL_PLANET = 'planet';
    public const COL_ROLE = 'role';
    public const COL_SENTINELS = 'sentinels';
    public const COL_SYSTEM = 'system';

    public function getURLName() : string
    {
        return self::URL_NAME;
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
        $outpost = ClassHelper::requireObjectInstanceOf(
            OutpostRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $outpost->getLabelLinked(),
            self::COL_PLANET => $outpost->getPlanet()->getLabelLinked(),
            self::COL_SYSTEM => $outpost->getPlanet()->getSolarSystem()->getLabelLinked(),
            self::COL_ROLE => $outpost->getRole()->getLabelLinked(),
            self::COL_SENTINELS => $outpost->getPlanet()->getSentinelLevel()->getLabelLinked()
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SYSTEM, t('Solar system'));
        $this->grid->addColumn(self::COL_PLANET, t('Planet'));
        $this->grid->addColumn(self::COL_ROLE, t('Role'));
        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));
    }

    protected function configureActions() : void
    {
    }

    protected function configureFilters() : void
    {
        $resource = $this->getResource();

        $this->filters->selectResource($resource);

        $this->filterSettings->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
        $this->grid->addHiddenVar(ResourcesCollection::PRIMARY_NAME, (string)$resource->getID());
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setAbstract(t(
                'These are all outposts that have access to %1$s, because their planet has it.',
                $this->getResource()->getLabel()
            ));
    }

    public function getNavigationTitle() : string
    {
        return t('Outposts');
    }

    public function getTitle() : string
    {
        return t('Outposts with resource');
    }
}
