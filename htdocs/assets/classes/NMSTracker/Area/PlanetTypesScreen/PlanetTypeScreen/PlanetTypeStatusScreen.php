<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen;

use Application_Admin_Area_Mode_Submode;
use NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen;
use NMSTracker\Interfaces\Admin\ViewPlanetTypeInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetTypeTrait;

/**
 * @property PlanetTypeScreen $mode
 */
class PlanetTypeStatusScreen
    extends Application_Admin_Area_Mode_Submode
    implements ViewPlanetTypeInterface
{
    use ViewPlanetTypeTrait;

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
        return t('Planet type overview');
    }

    public function getDefaultAction() : string
    {
        return '';
    }

    protected function _renderContent()
    {
        $grid = $this->ui->createPropertiesGrid();
        $type = $this->getPlanetType();

        $grid->addAmount(t('Planets'), $type->countPlanets());

        $grid->addHeader(t('Comments'));

        $grid->addMarkdown($type->getComments())
            ->ifEmpty(t('No comments entered.'));

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }
}
