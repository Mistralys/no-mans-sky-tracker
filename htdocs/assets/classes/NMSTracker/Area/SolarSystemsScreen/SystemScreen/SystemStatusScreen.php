<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen;
use NMSTracker\Interfaces\Admin\ViewSystemScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;

/**
 * @property SystemScreen $mode
 */
class SystemStatusScreen
    extends Application_Admin_Area_Mode_Submode
    implements ViewSystemScreenInterface
{
    use ViewSystemScreenTrait;

    public const URL_NAME = 'status';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    public function getNavigationTitle() : string
    {
        return t('Status');
    }

    public function getTitle() : string
    {
        return t('Solar system status');
    }

    public function getDefaultAction() : string
    {
        return '';
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->setSubtitle($this->getTitle());
    }

    protected function _handleBreadcrumb() : void
    {
        $this->breadcrumb->appendItem($this->getNavigationTitle())
            ->makeLinked($this->getSolarSystem()->getAdminStatusURL());
    }

    protected function _renderContent()
    {
        $system = $this->getSolarSystem();

        $grid = $this->ui->createPropertiesGrid();

        $system->getCluster()->injectCommonProperties($grid);
        $system->injectProperties($grid);

        $grid->addDate(t('Date added'), $system->getDateAdded())
            ->withTime()
            ->withDiff();

        $grid->addBoolean(t('Own discovery?'), $system->isOwnDiscovery())
            ->makeYesNo()
            ->setComment(t('Whether this is your own discovery.'));

        $grid->add(t('Discovered planets'), $system->countPlanets().'/'.$system->getAmountPlanets());
        $grid->add(t('Comments'), $system->getComments())
            ->ifEmpty(sb()->muted(t('No comments entered.')));

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }
}
