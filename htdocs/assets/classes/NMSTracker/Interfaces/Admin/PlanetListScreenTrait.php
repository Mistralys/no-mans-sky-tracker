<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker\PlanetsCollection;
use UI;

trait PlanetListScreenTrait
{
    /**
     * @return PlanetsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanets();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        if($record instanceof PlanetRecord)
        {
            return array(
                PlanetListScreenInterface::COL_LABEL => sb()->add($record->getLabelLinked())->add($record->getMoonIcon()),
                PlanetListScreenInterface::COL_TYPE => $record->getType()->getLabelLinked(),
                PlanetListScreenInterface::COL_FAUNA => $record->getFaunaAmountPretty(true),
                PlanetListScreenInterface::COL_SENTINELS => $record->getSentinelLevel()->getLabelLinked(),
                PlanetListScreenInterface::COL_SCAN_COMPLETE => UI::prettyBool($record->isScanComplete())->makeYesNo(),
                PlanetListScreenInterface::COL_PLANETFALL => UI::prettyBool($record->isPlanetFallMade())->makeYesNo(),
                PlanetListScreenInterface::COL_OUTPOSTS => sb()->link(
                    $record->countOutpostsPretty(),
                    $record->getAdminOutpostsURL()
                ),
                PlanetListScreenInterface::COL_RESOURCES => $record->getResourceFilters()->countItems()
            );
        }

        return array();
    }

    protected function configureColumns() : void
    {
        $filters = $this->getPlanetFilters();

        $this->grid->addColumn(PlanetListScreenInterface::COL_LABEL, t('Name'))
            ->setSortable(true, $filters->getColLabel());

        $this->grid->addColumn(PlanetListScreenInterface::COL_TYPE, t('Type'));

        $this->grid->addColumn(PlanetListScreenInterface::COL_SENTINELS, t('Sentinels'));

        $this->grid->addColumn(PlanetListScreenInterface::COL_SCAN_COMPLETE, t('Scan complete?'))
            ->alignCenter();

        $this->grid->addColumn(PlanetListScreenInterface::COL_PLANETFALL, t('Planet-fall?'))
            ->alignCenter();

        $this->grid->addColumn(PlanetListScreenInterface::COL_FAUNA, t('Fauna'))
            ->alignRight();

        $this->grid->addColumn(PlanetListScreenInterface::COL_OUTPOSTS, t('Outposts'))
            ->alignRight();

        $this->grid->addColumn(PlanetListScreenInterface::COL_RESOURCES, t('Resources'))
            ->alignRight();

        $this->grid->enableLimitOptionsDefault();
    }
}
