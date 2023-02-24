<?php
/**
 * File containing the {@see NMSTracker} class.
 * 
 * @package NMSTracker
 * @subpackage Core
 * @see NMSTracker
 *
 * @template-version 1.1
 */

declare(strict_types=1);

use AppUtils\ClassHelper;
use AppUtils\FileHelper\FileInfo;
use NMSTracker\Area\ClustersScreen;
use NMSTracker\Area\OutpostsScreen;
use NMSTracker\Area\PlanetsScreen;
use NMSTracker\Area\PlanetTypesScreen;
use NMSTracker\Area\POIsScreen;
use NMSTracker\Area\ResourcesScreen;
use NMSTracker\Area\SolarSystemsScreen;
use NMSTracker\Area\SpaceStationsScreen;
use NMSTracker\CustomIcon;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;

/**
 * Main driver class for the application.
 *
 * @package NMSTracker
 * @subpackage Core
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_Driver
 * 
 * @property NMSTracker_User $user
 * @property NMSTracker_Session $session
 * @property NMSTracker_Request $request
 */
class NMSTracker extends Application_Driver
{
    public function getPageParams(UI_Page $page) : array
    {
        return array();
    }
    
    protected function setUpUI() : void
    {
        $this->configureAdminUIFramework();
        
        $this->ui->addJavascriptHeadVariable('application.instanceID', APP_INSTANCE_ID);
    }
    
    public function getAppName() : string
    {
        return t('NMS Tracker');
    }
    
    public function getAppNameShort() : string
    {
        return t('NMS Tracker');
    }
    
    public function getAdminAreas() : array
    {
        return array(
            SolarSystemsScreen::URL_NAME => ClassHelper::getClassTypeName(SolarSystemsScreen::class),
            PlanetsScreen::URL_NAME => ClassHelper::getClassTypeName(PlanetsScreen::class),
            OutpostsScreen::URL_NAME => ClassHelper::getClassTypeName(OutpostsScreen::class),
            SpaceStationsScreen::URL_NAME => ClassHelper::getClassTypeName(SpaceStationsScreen::class),
            ResourcesScreen::URL_NAME => ClassHelper::getClassTypeName(ResourcesScreen::class),
            POIsScreen::URL_NAME => ClassHelper::getClassTypeName(POIsScreen::class),
            PlanetTypesScreen::URL_NAME => ClassHelper::getClassTypeName(PlanetTypesScreen::class),
            ClustersScreen::URL_NAME => ClassHelper::getClassTypeName(ClustersScreen::class),
            Application_Admin_Area_Settings::URL_NAME => ClassHelper::getClassTypeName(NMSTracker_Area_Settings::class),
            Application_Admin_TranslationsArea::URL_NAME => ClassHelper::getClassTypeName(NMSTracker_Area_Translations::class),
            NMSTracker_Area_Devel::URL_NAME => ClassHelper::getClassTypeName(NMSTracker_Area_Devel::class)
        );
    }
    
    public function getRevisionableTypes() : array
    {
        return array();
    }
    
    protected static ?NMSTracker_Session $session = null;
    
    public static function getSession() : NMSTracker_Session
    {
        if(!isset(self::$session)) {
            self::$session = new NMSTracker_Session();
        }
        
        return self::$session;
    }
    
    protected ?string $extendedVersion = null;
    
    public function getExtendedVersion() : string
    {
        if(!isset($this->extendedVersion)) {
            $this->extendedVersion = FileInfo::factory(APP_ROOT.'/version')->getContents();
        }
        
        return $this->extendedVersion;
    }

    public static function icon() : CustomIcon
    {
        return new CustomIcon();
    }
}
