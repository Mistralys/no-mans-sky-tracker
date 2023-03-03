<?php

declare(strict_types=1);

namespace NMSTracker\Area\TagsScreen\TagScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use DBHelper_BaseCollection;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\PlanetListScreenInterface;
use NMSTracker\Interfaces\Admin\PlanetListScreenTrait;
use NMSTracker\Interfaces\Admin\ViewTagScreenInterface;
use NMSTracker\Interfaces\Admin\ViewTagScreenTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\PlanetsCollection;
use NMSTracker\TagsCollection;
use NMSTracker_User;

/**
 * @property PlanetFilterCriteria $filters
 * @property NMSTracker_User $user
 */
class TagPlanetsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements
    ViewTagScreenInterface,
    PlanetListScreenInterface
{
    use ViewTagScreenTrait;
    use PlanetListScreenTrait;

    public const URL_NAME = 'planets';

    public function getDefaultAction() : string
    {
        return '';
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewTags();
    }

    public function getNavigationTitle() : string
    {
        return t('Planets');
    }

    public function getTitle() : string
    {
        return t('Planets');
    }

    protected function configureFilters() : void
    {
        $this->filters->selectTag($this->getTag());
    }

    protected function configureActions() : void
    {
    }

    /**
     * @return PlanetsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    public function getPlanetFilters() : PlanetFilterCriteria
    {
        return $this->filters;
    }

    public function getPersistVars() : array
    {
        return array(
            TagsCollection::PRIMARY_NAME => $this->getTag()->getID()
        );
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getTag()->getCollection()->getAdminListURL();
    }
}
