<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionRecord;
use NMSTracker\Outposts\OutpostRecord;
use DBHelper_BaseCollection;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewOutpostScreenInterface;
use NMSTracker\Interfaces\Admin\ViewOutpostScreenTrait;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;

/**
 * @property OutpostRecord $record
 */
class OutpostStatusScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionRecord
    implements ViewOutpostScreenInterface
{
    use ViewSystemScreenTrait;
    use ViewPlanetScreenTrait;
    use ViewOutpostScreenTrait;

    public const URL_NAME = 'outpost-status';

    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createOutposts();
    }

    protected function getRecordMissingURL() : string
    {
        return $this->getPlanet()->getAdminOutpostsURL();
    }

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
        return t('Outpost status');
    }

    public function getAbstract() : string
    {
        return '';
    }

    protected function _renderContent()
    {
        $outpost = $this->getOutpost();
        $planet = $this->getPlanet();
        $system = $planet->getSolarSystem();

        $grid = $this->ui->createPropertiesGrid();
        $grid->add(t('Role'), $outpost->getRole()->getLabelLinked());

        $grid->addHeader(NMSTracker::icon()->planet().' '.t('Planet'));
        $grid->add(
            t('Planet name'),
            t(
                '%1$s in system %2$s',
                $planet->getLabelLinked(),
                $system->getLabelLinked(),
            )
        )
            ->setComment(sb()
                ->t('Star type:')->add($system->getStarType()->getLabel())
                ->add('|')
                ->t('Dominant race:')->add($system->getRace()->getLabel())
            );

        $grid->add(t('Distance from core'), $system->getCoreDistancePretty());
        $planet->injectProperties($grid);

        $grid->addHeader(NMSTracker::icon()->services().' '.t('Services'));
        $grid->addMerged($outpost->getServiceFilters()->getContainer()->renderBulletList());

        return $this->renderer
            ->appendContent($grid)
            ->makeWithoutSidebar();
    }
}
