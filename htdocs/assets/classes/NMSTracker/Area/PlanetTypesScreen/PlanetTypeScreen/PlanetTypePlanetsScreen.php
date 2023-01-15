<?php

declare(strict_types=1);

namespace NMSTracker\Area\PlanetTypesScreen\PlanetTypeScreen;

use Application_Admin_Area_Mode_Submode_CollectionList;
use AppUtils\ClassHelper;
use classes\NMSTracker\Planets\PlanetRecord;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetTypeInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetTypeTrait;
use NMSTracker\Planets\PlanetFilterCriteria;
use NMSTracker\Planets\PlanetFilterSettings;
use NMSTracker\PlanetsCollection;
use NMSTracker\PlanetTypesCollection;

/**
 * @property PlanetFilterCriteria $filters
 * @property PlanetFilterSettings $filterSettings
 */
class PlanetTypePlanetsScreen
    extends Application_Admin_Area_Mode_Submode_CollectionList
    implements ViewPlanetTypeInterface
{
    public const URL_NAME = 'planets';
    public const COL_LABEL = 'label';
    public const COL_SENTINELS = 'sentinels';

    use ViewPlanetTypeTrait;

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return PlanetsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $planet = ClassHelper::requireObjectInstanceOf(
            PlanetRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $planet->getLabelLinked(),
            self::COL_SENTINELS => $planet->getSentinelLevel()->getLabelLinked()
        );
    }

    protected function configureFilters() : void
    {
        $type = $this->getPlanetType();

        $this->filters->selectPlanetType($type);
        $this->filterSettings->setSettingEnabled(PlanetFilterSettings::SETTING_TYPE, false);

        $this->filterSettings->addHiddenVar(PlanetTypesCollection::PRIMARY_NAME, (string)$type->getID());
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, $this->filters->getColLabel());

        $this->grid->addColumn(self::COL_SENTINELS, t('Sentinels'));
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return ClassFactory::createPlanetTypes()->getAdminListURL();
    }

    public function getNavigationTitle() : string
    {
        return t('Planets');
    }

    public function getTitle() : string
    {
        return t('Planets by type');
    }
}
