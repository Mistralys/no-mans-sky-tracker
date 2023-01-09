<?php
/**
 * File containing the {@see NMSTracker_Area_Settings} class.
 * @package NMSTracker
 * @subpackage Administration
 * @see NMSTracker_Area_Settings
 *
 * @template-version 1.1
 */
 
declare(strict_types=1);

/**
 * The user settings screen where the user can change his preferences.
 *
 * @package NMSTracker
 * @subpackage Administration
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_Admin_Area_Settings
 *
 * @property NMSTracker $driver
 * @property NMSTracker_User $user
 * @property NMSTracker_Session $session
 * @property NMSTracker_Request $request
 */
class NMSTracker_Area_Settings extends Application_Admin_Area_Settings
{
    public function getNavigationGroup() : string
    {
        return t('Manage');
    }
}
