<?php
/**
 * File containing the {@see NMSTracker_Area_Translations} class.
 *
 * @package NMSTracker
 * @subpackage Administration
 * @see NMSTracker_Area_Translations
 * 
 * @template-version 1
 */

declare(strict_types=1);

/**
 * Displays the UI to translate the application's texts.
 *
 * @package NMSTracker
 * @subpackage Administration
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_Admin_TranslationsArea
 *
 * @property NMSTracker $driver
 * @property NMSTracker_User $user
 * @property NMSTracker_Session $session
 * @property NMSTracker_Request $request
 */
class NMSTracker_Area_Translations extends Application_Admin_TranslationsArea
{
    public function isUserAllowed() : bool
    {
        return $this->user->canTranslateUI();
    }

    public function getNavigationGroup() : string
    {
        return t('Manage');
    }
}
