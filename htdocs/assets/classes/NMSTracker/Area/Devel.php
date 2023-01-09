<?php
/**
 * File containing the {@see NMSTracker_Area_Devel} class.
 * @package NMSTracker
 * @subpackage Administration
 * @see NMSTracker_Area_Devel
 *
 * @template-version 1.2
 */

declare(strict_types=1);

/**
 * The main developer tools area.
 *
 * @package NMSTracker
 * @subpackage Administration
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_Admin_Area_Devel
 */
class NMSTracker_Area_Devel extends Application_Admin_Area_Devel
{
	protected function initItems() : void
    {
        $this->registerMaintenance();
        $this->registerAppSettings();
        $this->registerAppInterface();
        $this->registerAppSets();
        $this->registerErrorLog();
        $this->registerAppLogs();
        $this->registerDBDumps();
        $this->registerRightsOverview();

        // register custom items
        //$this->registerItem('urlname', t('Label'), t('Category name'));
    }
}
