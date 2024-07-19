<?php

declare(strict_types=1);

namespace NMSTracker\User\Roles;

use Application\User\Roles\BaseRole;
use NMSTracker_User;

class SuperAdminRole extends BaseRole
{
    public const ROLE_ID = 'SuperAdmin';

    public function getLabel(): string
    {
        return t('Super Admin');
    }

    public function getRights(): array
    {
        return array(
            NMSTracker_User::RIGHT_VIEW_PLANETS,
            NMSTracker_User::RIGHT_VIEW_SOLAR_SYSTEMS,
            NMSTracker_User::RIGHT_VIEW_OUTPOSTS,
            NMSTracker_User::RIGHT_VIEW_PLANET_TYPES,
            NMSTracker_User::RIGHT_VIEW_POIS,
            NMSTracker_User::RIGHT_VIEW_SPACE_STATIONS,
            NMSTracker_User::RIGHT_VIEW_RESOURCES,
            NMSTracker_User::RIGHT_VIEW_TAGS,

            NMSTracker_User::RIGHT_EDIT_PLANETS,
            NMSTracker_User::RIGHT_EDIT_SOLAR_SYSTEMS,
            NMSTracker_User::RIGHT_EDIT_OUTPOSTS,
            NMSTracker_User::RIGHT_EDIT_PLANET_TYPES,
            NMSTracker_User::RIGHT_EDIT_POIS,
            NMSTracker_User::RIGHT_EDIT_SPACE_STATIONS,
            NMSTracker_User::RIGHT_EDIT_RESOURCES,
            NMSTracker_User::RIGHT_EDIT_TAGS,

            NMSTracker_User::RIGHT_CREATE_PLANETS,
            NMSTracker_User::RIGHT_CREATE_SOLAR_SYSTEMS,
            NMSTracker_User::RIGHT_CREATE_OUTPOSTS,
            NMSTracker_User::RIGHT_CREATE_PLANET_TYPES,
            NMSTracker_User::RIGHT_CREATE_POIS,
            NMSTracker_User::RIGHT_CREATE_SPACE_STATIONS,
            NMSTracker_User::RIGHT_CREATE_RESOURCES,
            NMSTracker_User::RIGHT_CREATE_TAGS
        );
    }

    public function getID(): string
    {
        return self::ROLE_ID;
    }
}
