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

    public const RIGHT_VIEW_SOLAR_SYSTEMS = 'view_solar_sytems';
    public const RIGHT_VIEW_PLANETS = 'view_planets';
    public const RIGHT_VIEW_OUTPOSTS = 'view_outposts';
    public const RIGHT_CREATE_PLANETS = 'create_planets';
    public const RIGHT_EDIT_PLANETS = 'edit_planets';
    public const RIGHT_CREATE_SOLAR_SYSTEMS = 'create_solar_systems';
    public const RIGHT_EDIT_SOLAR_SYSTEMS = 'edit_solar_systems';
    public const RIGHT_EDIT_OUTPOSTS = 'edit_outposts';
    public const RIGHT_CREATE_OUTPOSTS = 'create_outposts';

    public function getRightGroups(): array
    {
        return array(
            self::GROUP_SOLAR_SYSTEMS => t('Manage solar systems'),
            self::GROUP_MANAGE_PLANETS => t('Manage planets'),
            self::GROUP_OUTPOSTS => t('Manage outposts')
        );
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
    }

    protected function registerRights_outposts(Application_User_Rights_Group $group) : void
    {
        $group->registerRight(self::RIGHT_VIEW_OUTPOSTS, t('View outposts'));
        $group->registerRight(self::RIGHT_EDIT_OUTPOSTS, t('Edit outposts'));
        $group->registerRight(self::RIGHT_CREATE_OUTPOSTS, t('Add outposts'));
    }

    protected function registerRoles(): void
    {
        $manager = $this->getRightsManager();

        $manager->registerRole('SuperAdmin', t('Super admin'))
            ->addRights(
                self::RIGHT_VIEW_PLANETS,
                self::RIGHT_VIEW_SOLAR_SYSTEMS,
                self::RIGHT_VIEW_OUTPOSTS,

                self::RIGHT_EDIT_PLANETS,
                self::RIGHT_EDIT_SOLAR_SYSTEMS,
                self::RIGHT_EDIT_OUTPOSTS,

                self::RIGHT_CREATE_PLANETS,
                self::RIGHT_CREATE_SOLAR_SYSTEMS,
                self::RIGHT_CREATE_OUTPOSTS
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
    // endregion
}
