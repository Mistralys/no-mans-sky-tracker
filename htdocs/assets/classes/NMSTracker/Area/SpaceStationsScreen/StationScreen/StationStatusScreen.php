<?php

declare(strict_types=1);

namespace NMSTracker\Area\SpaceStationsScreen\StationScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenInterface;
use NMSTracker\Interfaces\Admin\ViewSpaceStationScreenTrait;

class StationStatusScreen
    extends Application_Admin_Area_Mode_Submode
    implements ViewSpaceStationScreenInterface
{
    use ViewSpaceStationScreenTrait;

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
        return t('Space station status');
    }

    public function getDefaultAction() : string
    {
        return '';
    }

    protected function _renderContent()
    {
        $station = $this->getSpaceStation();
        $solarSystem = $station->getSolarSystem();
        $grid = $this->ui->createPropertiesGrid();

        $grid->add(t('Solar system'), $solarSystem->getLabel());
        $grid->add(t('Star type'), $solarSystem->getStarType()->getLabel());
        $grid->add(t('Dominant race'), $solarSystem->getRace()->getLabel());

        $grid->addHeader(t('Comments'));
        $grid->addMerged($station->getComments())
            ->ifEmpty(sb()->muted(t('No comments entered.')));

        $grid->addHeader(t('Sale offers'));
        $grid->addMerged($station->getSellOffers()->renderBulletList());

        $grid->addHeader(t('Buy offers'));
        $grid->addMerged($station->getBuyOffers()->renderBulletList());

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }
}
