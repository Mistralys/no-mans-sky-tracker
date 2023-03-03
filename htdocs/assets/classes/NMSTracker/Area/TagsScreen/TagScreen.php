<?php

declare(strict_types=1);

namespace NMSTracker\Area\TagsScreen;

use Application_Admin_Area_Mode;
use NMSTracker;
use NMSTracker\Area\SpaceStationsScreen\StationScreen\StationStatusScreen;
use NMSTracker\Area\TagsScreen\TagScreen\TagSettingsScreen;
use NMSTracker\Interfaces\Admin\ViewTagScreenInterface;
use NMSTracker\Interfaces\Admin\ViewTagScreenTrait;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class TagScreen
    extends Application_Admin_Area_Mode
    implements ViewTagScreenInterface
{
    use ViewTagScreenTrait;

    public const URL_NAME = 'tag';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getDefaultSubmode() : string
    {
        return TagSettingsScreen::URL_NAME;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewTags();
    }

    public function getNavigationTitle() : string
    {
        return t('View');
    }

    public function getTitle() : string
    {
        return t('Tag details');
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->tags())
            ->setText($this->getTag()->getLabel());
    }

    protected function _handleBreadcrumb() : void
    {
        $tag = $this->getTag();

        $this->breadcrumb->appendArea($this->area);

        $this->breadcrumb->appendItem(t('View'))
            ->makeLinked($tag->getAdminViewURL());
    }

    protected function _handleSubnavigation() : void
    {
        $tag = $this->getTag();

        $this->subnav->addURL(t('Settings'), $tag->getAdminSettingsURL())
            ->setIcon(NMSTracker::icon()->settings());
    }
}
