<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use AppUtils\ClassHelper;
use AppUtils\OutputBuffering;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker\ClassFactory;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker\PlanetsCollection;
use NMSTracker_AjaxMethods_SetPlanetFallMade;
use NMSTracker_AjaxMethods_SetPlanetScanComplete;
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
                PlanetListScreenInterface::COL_SCAN_COMPLETE => $this->renderScanComplete($record),
                PlanetListScreenInterface::COL_PLANETFALL => $this->renderPlanetFallToggle($record),
                PlanetListScreenInterface::COL_OUTPOSTS => sb()->link(
                    $record->countOutpostsPretty(),
                    $record->getAdminOutpostsURL()
                ),
                PlanetListScreenInterface::COL_RESOURCES => $record->getResourceFilters()->countItems()
            );
        }

        return array();
    }

    protected function renderScanComplete(PlanetRecord $record) : string
    {
        return $this->renderBooleanToggle(
            $record,
            ClassHelper::getClassTypeName(NMSTracker_AjaxMethods_SetPlanetScanComplete::class),
            t('Toggles the planet\'s fauna scan status.'),
            $record->isScanComplete()
        );
    }

    protected function renderPlanetFallToggle(PlanetRecord $record) : string
    {
        return $this->renderBooleanToggle(
            $record,
            ClassHelper::getClassTypeName(NMSTracker_AjaxMethods_SetPlanetFallMade::class),
            t('Toggles whether you have landed on the planet.'),
            $record->isPlanetFallMade()
        );
    }

    protected function renderBooleanToggle(
        PlanetRecord $record,
        string $ajaxMethod,
        string $tooltip,
        bool $status
    ) : string
    {
        $id = nextJSID();

        $this->getUI()->addJavascriptOnloadStatement(
            sprintf(
                "%s.RegisterToggle",
                $this->jsID
            ),
            $id,
            $ajaxMethod
        );

        OutputBuffering::start();
        ?>
        <div id="<?php echo $id ?>"
             data-planet-id="<?php echo $record->getID() ?>"
             class="clickable"
             title="<?php echo $tooltip ?>"
            >
            <div class="state-on" style="display:<?php if(!$status) {echo 'none';} ?>">
                <?php echo UI::prettyBool(true)->makeYesNo(); ?>
            </div>
            <div class="state-off" style="display:<?php if($status) {echo 'none';} ?>">
                <?php echo UI::prettyBool(false)->makeYesNo(); ?>
            </div>
        </div>
        <?php

        return OutputBuffering::get();
    }

    protected string $jsID;

    protected function configureColumns() : void
    {
        $this->jsID = nextJSID();

        $ui = $this->getUI();
        $ui->addJavascript('planet-list.js');
        $ui->addJavascriptHead(sprintf("let %s = new PlanetList()", $this->jsID));

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
