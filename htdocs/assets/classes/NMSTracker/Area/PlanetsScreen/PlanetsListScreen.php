<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use AppUtils\ClassHelper\ClassNotExistsException;
use AppUtils\ClassHelper\ClassNotImplementsException;
use AppUtils\ConvertHelper_Exception;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\PlanetsScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker_User;
use UI;
use UI_Renderable_Interface;

/**
 * @property NMSTracker_User $user
 * @property PlanetsScreen $area
 * @property PlanetFilterCriteria $filters
 */
class PlanetsListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'planets-list';

    public const COL_LABEL = 'label';
    public const COL_SYSTEM = 'system';
    public const COL_TYPE = 'type';
    public const COL_SENTINELS = 'sentinels';
    public const COL_SCAN = 'scan';
    public const COL_OUTPOSTS = 'outposts';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    /**
     * @param DBHelper_BaseRecord $record
     * @param DBHelper_BaseFilterCriteria_Record $entry
     * @return array|number[]|string[]|UI_Renderable_Interface[]
     * @throws ClassNotExistsException
     * @throws ClassNotImplementsException
     * @throws ConvertHelper_Exception
     */
    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $planet = ClassHelper::requireObjectInstanceOf(PlanetRecord::class, $record);
        $system = $planet->getSolarSystem();

        return array(
            self::COL_LABEL => $planet->getLabelLinked(),
            self::COL_SYSTEM => $system->getLabelLinked(),
            self::COL_TYPE => $planet->getType()->getLabelLinked(),
            self::COL_SENTINELS => $planet->getSentinelLevel()->getLabelLinked(),
            self::COL_SCAN => UI::prettyBool($planet->isScanComplete())->makeYesNo(),
            self::COL_OUTPOSTS => sb()->link(
                (string)$planet->countOutposts(),
                $planet->getAdminOutpostsURL()
            )
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Name'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SYSTEM, t('Solar system'));

        $this->grid->addColumn(self::COL_TYPE, t('Type'));

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));

        $this->grid->addColumn(self::COL_SCAN, t('Scan complete?'))
            ->alignCenter();

        $this->grid->addColumn(self::COL_OUTPOSTS, t('Outposts'))
            ->alignRight();
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
        return $this->user->canViewPlanets();
    }

    public function getNavigationTitle() : string
    {
        return t('Overview');
    }

    public function getTitle() : string
    {
        return t('Global planets overview');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->planet());
    }
}
