<?php
/**
 * File containing the {@see NMSTracker_User} class.
 * 
 * @package NMSTracker
 * @subpackage Core
 * @see NMSTracker_User
 */

declare(strict_types=1);

/**
 * Class for an authenticated user.
 *
 * @package NMSTracker
 * @subpackage Core
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_User
 *
 * @template-version 1.2
 */
class NMSTracker_User extends Application_User_Extended
{
    public const GROUP_MANAGE_PLANETS = 'planets';
    public const GROUP_SOLAR_SYSTEMS = 'solar_systems';
    public const GROUP_OUTPOSTS = 'outposts';
    public const GROUP_SPACE_STATIONS = 'space_stations';
    public const GROUP_RESOURCES = 'resources';

    public const RIGHT_VIEW_SOLAR_SYSTEMS = 'view_solar_systems';
    public const RIGHT_VIEW_PLANETS = 'view_planets';
    public const RIGHT_VIEW_OUTPOSTS = 'view_outposts';
    public const RIGHT_CREATE_PLANETS = 'create_planets';
    public const RIGHT_EDIT_PLANETS = 'edit_planets';
    public const RIGHT_CREATE_SOLAR_SYSTEMS = 'create_solar_systems';
    public const RIGHT_EDIT_SOLAR_SYSTEMS = 'edit_solar_systems';
    public const RIGHT_EDIT_OUTPOSTS = 'edit_outposts';
    public const RIGHT_CREATE_OUTPOSTS = 'create_outposts';
    public const RIGHT_VIEW_PLANET_TYPES = 'view_planet_types';
    public const RIGHT_EDIT_PLANET_TYPES = 'edit_planet_types';
    public const RIGHT_CREATE_PLANET_TYPES = 'create_planet_types';
    public const RIGHT_VIEW_POIS = 'view_pois';
    public const RIGHT_CREATE_POIS = 'create_pois';
    public const RIGHT_EDIT_POIS = 'edit_pois';
    public const RIGHT_VIEW_SPACE_STATIONS = 'view_space_stations';
    public const RIGHT_CREATE_SPACE_STATIONS = 'create_space_stations';
    public const RIGHT_EDIT_SPACE_STATIONS = 'edit_space_stations';
    public const RIGHT_VIEW_RESOURCES = 'view_resources';
    public const RIGHT_EDIT_RESOURCES = 'edit_resources';
    public const RIGHT_CREATE_RESOURCES = 'create_resources';

    public function getRightGroups(): array
    {
        return array(
            self::GROUP_SOLAR_SYSTEMS => t('Manage solar systems'),
            self::GROUP_MANAGE_PLANETS => t('Manage planets'),
            self::GROUP_OUTPOSTS => t('Manage outposts'),
            self::GROUP_SPACE_STATIONS => t('Manage space stations'),
            self::GROUP_RESOURCES => t('Manage resources')
        );
    }

    protected function registerRights_resources(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_RESOURCES, t('View resources'));
        $group->registerRight(self::RIGHT_EDIT_RESOURCES, t('Edit resources'));
        $group->registerRight(self::RIGHT_CREATE_RESOURCES, t('Create resources'));
    }

    protected function registerRights_solar_systems(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_SOLAR_SYSTEMS, t('View solar systems'));
        $group->registerRight(self::RIGHT_CREATE_SOLAR_SYSTEMS, t('Create solar systems'));
        $group->registerRight(self::RIGHT_EDIT_SOLAR_SYSTEMS, t('Edit solar systems'));
    }

    protected function registerRights_planets(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_PLANETS, t('View planets'));
        $group->registerRight(self::RIGHT_CREATE_PLANETS, t('Add planets'));
        $group->registerRight(self::RIGHT_EDIT_PLANETS, t('Edit planets'));

        $group->registerRight(self::RIGHT_VIEW_PLANET_TYPES, t('View planet types'));
        $group->registerRight(self::RIGHT_CREATE_PLANET_TYPES, t('Create planet types'));
        $group->registerRight(self::RIGHT_EDIT_PLANET_TYPES, t('Edit planet types'));

        $group->registerRight(self::RIGHT_VIEW_POIS, t('View points of interest'));
        $group->registerRight(self::RIGHT_CREATE_POIS, t('Add points of interest'));
        $group->registerRight(self::RIGHT_EDIT_POIS, t('Edit points of interest'));
    }

    protected function registerRights_outposts(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_OUTPOSTS, t('View outposts'));
        $group->registerRight(self::RIGHT_EDIT_OUTPOSTS, t('Edit outposts'));
        $group->registerRight(self::RIGHT_CREATE_OUTPOSTS, t('Add outposts'));
    }

    protected function registerRights_space_stations(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_SPACE_STATIONS, t('View space stations'));
        $group->registerRight(self::RIGHT_EDIT_SPACE_STATIONS, t('Edit space stations'));
        $group->registerRight(self::RIGHT_CREATE_SPACE_STATIONS, t('Add space stations'));
    }

    protected function registerRoles(): void
    {
        $manager = $this->getRightsManager();

        $manager->registerRole('SuperAdmin', t('Super admin'))
            ->addRights(
                self::RIGHT_VIEW_PLANETS,
                self::RIGHT_VIEW_SOLAR_SYSTEMS,
                self::RIGHT_VIEW_OUTPOSTS,
                self::RIGHT_VIEW_PLANET_TYPES,
                self::RIGHT_VIEW_POIS,
                self::RIGHT_VIEW_SPACE_STATIONS,
                self::RIGHT_VIEW_RESOURCES,

                self::RIGHT_EDIT_PLANETS,
                self::RIGHT_EDIT_SOLAR_SYSTEMS,
                self::RIGHT_EDIT_OUTPOSTS,
                self::RIGHT_EDIT_PLANET_TYPES,
                self::RIGHT_EDIT_POIS,
                self::RIGHT_EDIT_SPACE_STATIONS,
                self::RIGHT_EDIT_RESOURCES,

                self::RIGHT_CREATE_PLANETS,
                self::RIGHT_CREATE_SOLAR_SYSTEMS,
                self::RIGHT_CREATE_OUTPOSTS,
                self::RIGHT_CREATE_PLANET_TYPES,
                self::RIGHT_CREATE_POIS,
                self::RIGHT_CREATE_SPACE_STATIONS,
                self::RIGHT_CREATE_RESOURCES
            );
    }

    // region: Check methods

    public function canViewPlanets() : bool { return $this->can(self::RIGHT_VIEW_PLANETS); }
    public function canEditPlanets() : bool { return $this->can(self::RIGHT_EDIT_PLANETS); }
    public function canViewSolarSystems() : bool { return $this->can(self::RIGHT_VIEW_SOLAR_SYSTEMS); }
    public function canCreateSolarSystems() : bool { return $this->can(self::RIGHT_CREATE_SOLAR_SYSTEMS); }
    public function canEditSolarSystems() : bool { return $this->can(self::RIGHT_EDIT_SOLAR_SYSTEMS); }
    public function canViewOutposts() : bool { return $this->can(self::RIGHT_VIEW_OUTPOSTS); }
    public function canEditOutposts() : bool { return $this->can(self::RIGHT_EDIT_OUTPOSTS); }
    public function canCreateOutposts() : bool { return $this->can(self::RIGHT_CREATE_OUTPOSTS); }
    public function canEditPlanetTypes() : bool { return $this->can(self::RIGHT_EDIT_PLANET_TYPES); }
    public function canViewPlanetTypes() : bool { return $this->can(self::RIGHT_VIEW_PLANET_TYPES); }
    public function canCreatePlanetTypes() : bool { return $this->can(self::RIGHT_CREATE_PLANET_TYPES); }
    public function canViewPOIs() : bool { return $this->can(self::RIGHT_VIEW_POIS); }
    public function canCreatePOIs() : bool { return $this->can(self::RIGHT_CREATE_POIS); }
    public function canEditPOIs() : bool { return $this->can(self::RIGHT_EDIT_POIS); }
    public function canViewSpaceStations() : bool { return $this->can(self::RIGHT_VIEW_SPACE_STATIONS); }
    public function canCreateSpaceStations() : bool { return $this->can(self::RIGHT_CREATE_SPACE_STATIONS); }
    public function canEditSpaceStations() : bool { return $this->can(self::RIGHT_EDIT_SPACE_STATIONS); }
    public function canViewResources() : bool { return $this->can(self::RIGHT_VIEW_RESOURCES); }
    public function canEditResources() : bool { return $this->can(self::RIGHT_EDIT_RESOURCES); }
    public function canCreateResources() : bool { return $this->can(self::RIGHT_CREATE_RESOURCES); }
    // endregion
}
