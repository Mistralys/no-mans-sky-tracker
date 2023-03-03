<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use AppUtils\ClassHelper\ClassNotExistsException;
use AppUtils\ClassHelper\ClassNotImplementsException;
use AppUtils\ConvertHelper_Exception;
use NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\PlanetsScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\PlanetsCollection;
use NMSTracker_User;
use UI;
use UI_Renderable_Interface;

/**
 * @property NMSTracker_User $user
 * @property PlanetsScreen $area
 * @property PlanetFilterCriteria $filters
 */
class PlanetsListScreen
    extends Application_Admin_Area_Mode_CollectionList
    implements NMSTracker\Interfaces\Admin\PlanetListScreenInterface
{
    use NMSTracker\Interfaces\Admin\PlanetListScreenTrait;

    public const URL_NAME = 'planets-list';
    public const GRID_NAME = 'global-planets-list';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getGridName() : string
    {
        return self::GRID_NAME;
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return $this->filters;
    }

    protected function configureActions() : void
    {
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
