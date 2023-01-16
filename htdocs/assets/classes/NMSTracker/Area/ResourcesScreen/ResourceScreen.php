<?php

declare(strict_types=1);

namespace NMSTracker\Area\ResourcesScreen;

use Application_Admin_Area_Mode;
use NMSTracker;
use NMSTracker\Area\ResourcesScreen\ResourceScreen\ResourcePlanetsScreen;
use NMSTracker\Interfaces\Admin\ViewResourceScreenInterface;
use NMSTracker\Interfaces\Admin\ViewResourceScreenTrait;

class ResourceScreen
    extends Application_Admin_Area_Mode
    implements ViewResourceScreenInterface
{
    use ViewResourceScreenTrait;

    public const URL_NAME = 'view';

    public function getDefaultSubmode() : string
    {
        return ResourcePlanetsScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return true;
    }

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Details');
    }

    public function getTitle() : string
    {
        return t('Resource details');
    }

    protected function _handleHelp() : void
    {
        $resource = $this->getResource();

        $this->renderer
            ->getTitle()
            ->setText($resource->getLabel())
            ->setIcon(NMSTracker::icon()->resources());
    }

    protected function _handleSubnavigation() : void
    {
        $resource = $this->getResource();

        $this->subnav->addURL(
            t('Planets'),
            $resource->getAdminPlanetsURL()
        )
            ->setIcon(NMSTracker::icon()->planet());

        $this->subnav->addURL(
            t('Outposts'),
            $resource->getAdminOutpostsURL()
        )
            ->setIcon(NMSTracker::icon()->outpost());
    }
}
