<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\ClassHelper;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Multiselect;
use HTML_QuickForm2_Element_Select;
use HTML_QuickForm2_Element_Switch;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\SolarSystemsCollection;
use NMSTracker\UI\FormHelper;
use UI;

/**
 * @property SolarSystemRecord|NULL $record
 * @property SolarSystemsCollection $collection
 */
class SystemSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_RACE = 'race';
    public const SETTING_STAR = 'star';
    public const SETTING_COMMENTS = 'comments';
    public const SETTING_PLANETS = 'planets';
    public const SETTING_OWN_DISCOVERY = 'own_discovery';
    public const SETTING_WORMHOLE = 'wormhole';
    private FormHelper $formHelper;

    public function __construct(Application_Formable $formable, DBHelper_BaseCollection $collection, ?DBHelper_BaseRecord $record = null)
    {
        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);

        $this->formHelper = new FormHelper($this);
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function getCreateData(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function updateRecord(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function _afterSave(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $data) : void
    {
        $system = ClassHelper::requireObjectInstanceOf(SolarSystemRecord::class, $record);
        $id = $data->getKey(self::SETTING_WORMHOLE);

        if(!empty($id))
        {
            $system->setWormholeSystem(
                $this->collection->getByID((int)$id)
            );
        }
        else
        {
            $system->setWormholeSystem(null);
        }

        $system->save();
    }

    // region: Settings

    protected function registerSettings() : void
    {
        $group = $this->addGroup(t('Settings'))
            ->setIcon(UI::icon()->settings())
            ->expand();

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(SolarSystemsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting('core_distance')
            ->setStorageName(SolarSystemsCollection::COL_CORE_DISTANCE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectCoreDistance')),
                array($this, 'injectCoreDistance')
            ));

        $group->registerSetting(self::SETTING_STAR)
            ->setStorageName(SolarSystemsCollection::COL_STAR_TYPE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectStarType')),
                array($this, 'injectStarType')
            ));

        $group->registerSetting(self::SETTING_RACE)
            ->setStorageName(SolarSystemsCollection::COL_RACE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectRace')),
                array($this, 'injectRace')
            ));

        $group->registerSetting(self::SETTING_PLANETS)
            ->setStorageName(SolarSystemsCollection::COL_AMOUNT_PLANETS)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectAmountPlanets')),
                array($this, 'injectAmountPlanets')
            ));

        $group->registerSetting(self::SETTING_OWN_DISCOVERY)
            ->setStorageName(SolarSystemsCollection::COL_IS_OWN_DISCOVERY)
            ->setDefaultValue('yes')
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectOwnDiscovery')),
                array($this, 'injectOwnDiscovery')
            ));

        $group = $this->addGroup(t('Wormhole'))
            ->setIcon(NMSTracker::icon()->wormhole());

        $group->registerSetting(self::SETTING_WORMHOLE)
            ->makeInternal()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectWormhole')),
                array($this, 'injectWormhole')
            ));

        $group = $this->addGroup(t('Comments'))
            ->setIcon(UI::icon()->comment());

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(SolarSystemsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));
    }

    private function injectCoreDistance(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementInteger($setting->getName(), t('Core distance'));
        $el->setComment((string)sb()
            ->t('The distance to the galaxy core from this solar system.')
            ->note()
            ->t('This can be seen by getting as close as possible to the system in the galaxy map.')
        );

        $this->setElementAppend($el, t('LY'));

        return $el;
    }

    private function injectWormhole(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Multiselect
    {
        $el = $this->addElementMultiselect($setting->getName(), t('Wormhole destination'));
        $el->setComment(t('If the system has a wormhole, select the system here that it leads to.'));
        $el->enableFiltering();

        $el->addOption(t('No wormhole present'), '');

        $systems = $this->collection->getAll();
        foreach($systems as $system)
        {
            $el->addOption($system->getLabel(), $system->getID());
        }

        return $el;
    }

    private function injectOwnDiscovery(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Switch
    {
        $el = $this->addElementSwitch($setting->getName(), t('Own discovery?'));
        $el->setComment(t('Is this a personal discovery, or by someone else?'));
        $el->makeYesNo();
        $el->setValues('yes', 'no');

        return $el;
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        return $this->formHelper->injectLabel($setting, t('Name'));
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        return $this->formHelper->injectComments($setting, t('Comments'));
    }

    private function injectAmountPlanets(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $min = 0;
        if(isset($this->record)) {
            $min = $this->record->countPlanets();
        }

        $el = $this->addElementInteger($setting->getName(), t('Orbital bodies'), null, $min);
        $el->addClass('input-xxlarge');
        $el->setComment((string)sb()
            ->t('The amount of planets and moons in the system.')
            ->t('Useful to keep track of any planets you have not landed on yet to discover them.')
        );

        return $el;
    }

    private function injectRace(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Dominant race'));

        $el->addOption(t('Please select...'), '');

        $races = ClassFactory::createRaces()->getAll();

        foreach($races as $race)
        {
            $el->addOption($race->getLabel(), $race->getID());
        }

        return $el;
    }

    private function injectStarType(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Star type'));

        $el->addOption(t('Please select...'), '');

        $starTypes = ClassFactory::createStarTypes()->getAll();

        foreach($starTypes as $starType)
        {
            $el->addOption($starType->getLabel(), $starType->getID());
        }

        return $el;
    }

    // endregion

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return ClassFactory::createUser()->canCreateSolarSystems();
    }
}
