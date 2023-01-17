<?php

declare(strict_types=1);

namespace NMSTracker\Area\POIsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker_User;

/**
 * @property NMSTracker_User $user
 */
class POIsListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';

    public const COL_LABEL = 'label';
    public const COL_PLANET = 'planet';
    public const COL_SOLAR_SYSTEM = 'system';
    public const COL_SENTINELS = 'sentinels';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return PlanetPOIsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $poi = ClassHelper::requireObjectInstanceOf(
            PlanetPOIRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $poi->getLabelLinked(),
            self::COL_SOLAR_SYSTEM => $poi->getSolarSystem()->getLabelLinked(),
            self::COL_PLANET => $poi->getPlanet()->getLabelLinked(),
            self::COL_SENTINELS => $poi->getPlanet()->getSentinelLevel()->getLabelLinked()
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, PlanetPOIsCollection::COL_LABEL);

        $this->grid->addColumn(self::COL_SOLAR_SYSTEM, t('Solar system'));

        $this->grid->addColumn(self::COL_PLANET, t('Planet'));

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewPOIs();
    }

    public function getNavigationTitle() : string
    {
        return t('List');
    }

    public function getTitle() : string
    {
        return t('Available points of interest');
    }
}
